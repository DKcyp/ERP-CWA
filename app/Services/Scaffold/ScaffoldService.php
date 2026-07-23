<?php

namespace App\Services\Scaffold;

use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleMenu;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class ScaffoldService
{
    protected array $fieldBlueprintMap = [
        'string' => '$table->string(\'%s\')',
        'text' => '$table->text(\'%s\')',
        'longText' => '$table->longText(\'%s\')',
        'integer' => '$table->integer(\'%s\')',
        'unsignedInteger' => '$table->unsignedInteger(\'%s\')',
        'bigInteger' => '$table->bigInteger(\'%s\')',
        'tinyInteger' => '$table->tinyInteger(\'%s\')',
        'boolean' => '$table->boolean(\'%s\')',
        'decimal' => '$table->decimal(\'%s\', 12, 2)',
        'float' => '$table->float(\'%s\')',
        'double' => '$table->double(\'%s\')',
        'date' => '$table->date(\'%s\')',
        'datetime' => '$table->dateTime(\'%s\')',
        'time' => '$table->time(\'%s\')',
        'json' => '$table->json(\'%s\')',
    ];

    protected array $typeRuleMap = [
        'string' => ["'string'", "'max:255'"],
        'text' => ["'string'"],
        'longText' => ["'string'"],
        'integer' => ["'integer'"],
        'unsignedInteger' => ["'integer'"],
        'bigInteger' => ["'integer'"],
        'tinyInteger' => ["'integer'"],
        'boolean' => ["'boolean'"],
        'decimal' => ["'numeric'"],
        'float' => ["'numeric'"],
        'double' => ["'numeric'"],
        'date' => ["'date'"],
        'datetime' => ["'date'"],
        'time' => ["'date_format:H:i'"],
        'json' => ["'json'"],
    ];

    protected array $castMap = [
        'boolean' => "'%s' => 'boolean',",
        'date' => "'%s' => 'date',",
        'datetime' => "'%s' => 'datetime',",
        'decimal' => "'%s' => 'float',",
        'float' => "'%s' => 'float',",
        'double' => "'%s' => 'float',",
        'integer' => "'%s' => 'integer',",
        'unsignedInteger' => "'%s' => 'integer',",
        'bigInteger' => "'%s' => 'integer',",
        'tinyInteger' => "'%s' => 'integer',",
        'json' => "'%s' => 'array',",
    ];

    public function generate(array $payload): array
    {
        $resource = Str::studly($payload['resource_name'] ?? 'Resource');
        $table = Str::snake($payload['table_name'] ?? Str::pluralStudly($resource));
        $routeSlug = Str::kebab(Str::pluralStudly($resource));
        $viewFolder = $routeSlug;
        $controllerName = "{$resource}Controller";

        $fields = collect($payload['fields'] ?? [])
            ->map(function ($field) {
                $default = $field['default'] ?? null;
                if (is_string($default)) {
                    $default = trim($default);
                }

                return [
                    'name' => Str::snake($field['name'] ?? ''),
                    'type' => $field['type'] ?? 'string',
                    'nullable' => (int) ($field['nullable'] ?? 0) === 1,
                    'unique' => (int) ($field['unique'] ?? 0) === 1,
                    'default' => $default === '' ? null : $default,
                ];
            })
            ->filter(fn ($field) => $field['name'] !== '')
            ->unique('name')
            ->values();

        if ($fields->isEmpty()) {
            throw new RuntimeException('Field list cannot be empty.');
        }

        $paths = $this->buildPaths($resource, $table, $viewFolder);

        $viewDirectory = $paths['views'];

        $this->writeMigration($paths['migration'], $table, $fields->all());
        $this->writeModel($paths['model'], $resource, $table, $fields->all());
        $this->writeController($paths['controller'], $controllerName, $resource, $viewFolder, $routeSlug, $table, $fields->all());
        $viewFiles = $this->writeView($viewDirectory, $resource, $fields->all(), $routeSlug, $viewFolder);
        $this->appendRoutes($controllerName, $routeSlug, $resource);
        $this->createMenuItem($payload, $routeSlug, $resource);

        $paths['view_directory'] = $viewDirectory;
        $paths['views'] = $viewFiles;

        return $paths;
    }

    protected function buildPaths(string $resource, string $table, string $viewFolder): array
    {
        $timestamp = Carbon::now();
        $migrationTimestamp = $timestamp->format('Y_m_d_His');
        $migrationPath = database_path("migrations/{$migrationTimestamp}_create_{$table}_table.php");

        while (File::exists($migrationPath)) {
            $timestamp = $timestamp->addSecond();
            $migrationTimestamp = $timestamp->format('Y_m_d_His');
            $migrationPath = database_path("migrations/{$migrationTimestamp}_create_{$table}_table.php");
        }

        return [
            'controller' => app_path("Http/Controllers/{$resource}Controller.php"),
            'model' => app_path("Models/{$resource}.php"),
            'migration' => $migrationPath,
            'views' => resource_path("views/{$viewFolder}"),
            'route' => Str::kebab(Str::pluralStudly($resource)),
        ];
    }

    protected function writeMigration(string $path, string $table, array $fields): void
    {
        $lines = [];
        foreach ($fields as $field) {
            $blueprint = $this->fieldBlueprintMap[$field['type']] ?? $this->fieldBlueprintMap['string'];
            $definition = sprintf($blueprint, $field['name']);

            if ($field['nullable']) {
                $definition .= '->nullable()';
            }

            if ($field['unique']) {
                $definition .= '->unique()';
            }

            if ($this->hasDefault($field)) {
                $definition .= '->default(' . $this->formatDefault($field) . ')';
            }

            $lines[] = '            ' . $definition . ';';
        }

        $fieldsBlock = implode("\n", $lines);
        if (!empty($fieldsBlock)) {
            $fieldsBlock .= "\n";
        }

        $migration = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{$table}', function (Blueprint \$table) {
            \$table->id();
{$fieldsBlock}            \$table->timestamps();
            \$table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$table}');
    }
};
PHP;

        File::put($path, $migration);
    }

    protected function writeModel(string $path, string $model, string $table, array $fields): void
    {
        $fillable = array_map(fn ($field) => $field['name'], $fields);
        $fillableExport = $this->exportArray($fillable, 8);

        $castsLines = [];
        foreach ($fields as $field) {
            if (isset($this->castMap[$field['type']])) {
                $castsLines[] = '        ' . sprintf($this->castMap[$field['type']], $field['name']);
            }
        }

        $castsBlock = empty($castsLines)
            ? ''
            : "\n    protected \$casts = [\n" . implode("\n", $castsLines) . "\n    ];\n";

        $modelContent = <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class {$model} extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected \$table = '{$table}';

    protected \$fillable = {$fillableExport};
{$castsBlock}}
PHP;

        File::put($path, $modelContent);
    }

    protected function writeController(
        string $path,
        string $controller,
        string $model,
        string $viewFolder,
        string $routeSlug,
        string $table,
        array $fields
    ): void {
        $modelVar = Str::camel($model);
        $modelClass = "App\\Models\\{$model}";
        [$storeRules, $updateRules, $needsRule] = $this->buildValidationRules($fields, $table, $modelVar);

        $normalizerStore = $this->buildNormalizer($fields, 'validated');
        $normalizerUpdate = $this->buildNormalizer($fields, 'validated');

        $booleanColumns = '';
        foreach ($fields as $field) {
            if ($field['type'] === 'boolean') {
                $name = $field['name'];
                $booleanColumns .= "            ->editColumn('{$name}', fn (\$row) => \$row->{$name} ? 'Ya' : 'Tidak')\n";
            }
        }

        $ruleImport = $needsRule ? "use Illuminate\\Validation\\Rule;\n\n" : '';

        $template = <<<'TPL'
<?php

namespace App\Http\Controllers;

use {{MODEL_CLASS}};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
{{RULE_IMPORT}}class {{CONTROLLER}} extends Controller
{
    public function index(): View
    {
        return view('{{VIEW_FOLDER}}.index');
    }

    public function table(Request $request): JsonResponse
    {
        $query = {{MODEL}}::query()->latest();

        return DataTables::of($query)
            ->addIndexColumn()
{{BOOLEAN_COLUMNS}}            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row->id . '"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row->id . '"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function store(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), {{STORE_RULES}})
            ->validate();
{{NORMALIZE_STORE}}
        {{MODEL}}::create($validated);

        return response()->json([
            'message' => '{{MODEL}} created successfully.',
        ]);
    }

    public function show({{MODEL}} ${{MODEL_VAR}}): JsonResponse
    {
        return response()->json([
            'data' => ${{MODEL_VAR}},
        ]);
    }

    public function update(Request $request, {{MODEL}} ${{MODEL_VAR}}): JsonResponse
    {
        $validated = Validator::make($request->all(), {{UPDATE_RULES}})
            ->validate();
{{NORMALIZE_UPDATE}}
        ${{MODEL_VAR}}->update($validated);

        return response()->json([
            'message' => '{{MODEL}} updated successfully.',
        ]);
    }

    public function destroy({{MODEL}} ${{MODEL_VAR}}): JsonResponse
    {
        ${{MODEL_VAR}}->delete();

        return response()->json([
            'message' => '{{MODEL}} deleted successfully.',
        ]);
    }
}
TPL;

        $replacements = [
            '{{MODEL_CLASS}}' => $modelClass,
            '{{RULE_IMPORT}}' => $ruleImport,
            '{{CONTROLLER}}' => $controller,
            '{{VIEW_FOLDER}}' => $viewFolder,
            '{{MODEL}}' => $model,
            '{{MODEL_VAR}}' => $modelVar,
            '{{STORE_RULES}}' => $storeRules,
            '{{UPDATE_RULES}}' => $updateRules,
            '{{NORMALIZE_STORE}}' => $normalizerStore,
            '{{NORMALIZE_UPDATE}}' => $normalizerUpdate,
            '{{BOOLEAN_COLUMNS}}' => $booleanColumns,
        ];

        $controllerContent = strtr($template, $replacements);

        File::put($path, $controllerContent);
    }

    protected function buildValidationRules(array $fields, string $table, string $modelVar): array
    {
        $storeLines = [];
        $updateLines = [];
        $needsRule = false;

        foreach ($fields as $field) {
            $name = $field['name'];
            $rules = [];
            $rules[] = $field['nullable'] ? "'nullable'" : "'required'";

            $typeRules = $this->typeRuleMap[$field['type']] ?? ["'string'"];
            $rules = array_merge($rules, $typeRules);

            $storeRules = $rules;
            $updateRules = $rules;

            if ($field['unique']) {
                $needsRule = true;
                $storeRules[] = "'unique:{$table},{$name}'";
                $updateRules[] = "Rule::unique('{$table}', '{$name}')->ignore(\${$modelVar}->id)";
            }

            $storeLines[] = "            '{$name}' => [" . implode(', ', $storeRules) . "],";
            $updateLines[] = "            '{$name}' => [" . implode(', ', $updateRules) . "],";
        }

        $store = empty($storeLines) ? "[\n        ]" : "[\n" . implode("\n", $storeLines) . "\n        ]";
        $update = empty($updateLines) ? "[\n        ]" : "[\n" . implode("\n", $updateLines) . "\n        ]";

        return [$store, $update, $needsRule];
    }

    protected function exportArray(array $items, int $indentSpaces = 4): string
    {
        if (empty($items)) {
            return '[]';
        }

        $indent = str_repeat(' ', $indentSpaces);
        $lines = array_map(fn ($item) => "{$indent}'{$item}',", $items);
        return "[\n" . implode("\n", $lines) . "\n" . str_repeat(' ', $indentSpaces - 4) . ']';
    }

    protected function writeView(string $directory, string $model, array $fields, string $routeSlug, string $viewFolder): array
    {
        File::ensureDirectoryExists($directory);

        $title = Str::headline(Str::pluralStudly($model));
        $singularTitle = Str::headline($model);
        $tableId = "table-{$routeSlug}";
        $modalId = "modal-{$routeSlug}";
        $modalLabelId = "{$modalId}Label";
        $formId = "form-{$routeSlug}";
        $methodInputId = "form-method-{$routeSlug}";
        $btnAddId = "btn-add-{$routeSlug}";
        $resourceVar = Str::camel($model);
        $resourceIdField = "{$resourceVar}_id";

        $tableHeaders = implode("\n", array_map(function ($field) {
            return '                            <th>' . Str::headline($field['name']) . '</th>';
        }, $fields));

        $columnsJs = "            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },\n";
        foreach ($fields as $field) {
            $columnsJs .= "            { data: '{$field['name']}', name: '{$field['name']}' },\n";
        }
        $columnsJs .= "            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' }";

        $formFields = [];
        $fillFields = [];
        foreach ($fields as $field) {
            $name = $field['name'];
            $label = Str::headline($name);
            $inputId = "field_{$name}";

            $formFields[] = match ($field['type']) {
                'text', 'longText' => <<<HTML
            <div class="mb-3">
                <label for="{$inputId}" class="form-label">{$label}</label>
                <textarea class="form-control" id="{$inputId}" name="{$name}" rows="3" placeholder="Masukkan {$label}"></textarea>
            </div>
HTML,
                'boolean' => <<<HTML
            <div class="form-check mb-3">
                <input type="hidden" name="{$name}" value="0">
                <input class="form-check-input" type="checkbox" value="1" id="{$inputId}" name="{$name}">
                <label class="form-check-label" for="{$inputId}">{$label}</label>
            </div>
HTML,
                'integer', 'unsignedInteger', 'bigInteger', 'tinyInteger' => <<<HTML
            <div class="mb-3">
                <label for="{$inputId}" class="form-label">{$label}</label>
                <input type="number" class="form-control" id="{$inputId}" name="{$name}" placeholder="Masukkan {$label}">
            </div>
HTML,
                'decimal' => <<<HTML
            <div class="mb-3">
                <label for="{$inputId}" class="form-label">{$label}</label>
                <input type="number" step="0.01" class="form-control" id="{$inputId}" name="{$name}" placeholder="Masukkan {$label}">
            </div>
HTML,
                'float', 'double' => <<<HTML
            <div class="mb-3">
                <label for="{$inputId}" class="form-label">{$label}</label>
                <input type="number" step="any" class="form-control" id="{$inputId}" name="{$name}" placeholder="Masukkan {$label}">
            </div>
HTML,
                'date' => <<<HTML
            <div class="mb-3">
                <label for="{$inputId}" class="form-label">{$label}</label>
                <input type="date" class="form-control" id="{$inputId}" name="{$name}">
            </div>
HTML,
                'datetime' => <<<HTML
            <div class="mb-3">
                <label for="{$inputId}" class="form-label">{$label}</label>
                <input type="datetime-local" class="form-control" id="{$inputId}" name="{$name}">
            </div>
HTML,
                'time' => <<<HTML
            <div class="mb-3">
                <label for="{$inputId}" class="form-label">{$label}</label>
                <input type="time" class="form-control" id="{$inputId}" name="{$name}">
            </div>
HTML,
                'json' => <<<HTML
            <div class="mb-3">
                <label for="{$inputId}" class="form-label">{$label}</label>
                <textarea class="form-control" id="{$inputId}" name="{$name}" rows="3" placeholder="Masukkan {$label} dalam format JSON"></textarea>
            </div>
HTML,
                default => <<<HTML
            <div class="mb-3">
                <label for="{$inputId}" class="form-label">{$label}</label>
                <input type="text" class="form-control" id="{$inputId}" name="{$name}" placeholder="Masukkan {$label}">
            </div>
HTML,
            };

            $fillFields[] = match ($field['type']) {
                'boolean' => "        $('#{$inputId}').prop('checked', !!(data.{$name} ?? false));",
                'date' => "        $('#{$inputId}').val(data.{$name} ? String(data.{$name}).substring(0, 10) : '');",
                'datetime' => "        $('#{$inputId}').val(data.{$name} ? String(data.{$name}).replace(' ', 'T').substring(0, 16) : '');",
                'json' => "        $('#{$inputId}').val(data.{$name} ? JSON.stringify(data.{$name}) : '');",
                default => "        $('#{$inputId}').val(data.{$name} ?? '');",
            };
        }

        $formFieldsHtml = implode("\n", $formFields);
        $fillFieldsJs = implode("\n", $fillFields);

        $template = <<<'BLADE'
@extends('layouts.layout')

@section('title', ':TITLE')

@section('content')
<div class="page-heading mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h3>:TITLE</h3>
    <button type="button" class="btn btn-primary" id=":BTN_ID">
        <i class="bi bi-plus-lg me-1"></i> Tambah :SINGULAR
    </button>
</div>

<div class="page-content">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id=":TABLE_ID">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">No</th>
:TABLE_HEADERS
                            <th style="width: 120px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id=":MODAL_ID" tabindex="-1" aria-labelledby=":MODAL_LABEL" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id=":MODAL_LABEL">Tambah :SINGULAR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id=":FORM_ID">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="_method" id=":METHOD_INPUT_ID" value="POST">
                    <input type="hidden" id=":RESOURCE_ID_FIELD">
:FORM_FIELDS
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-floppy"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('after-style')
<style>
    #:TABLE_ID thead th { font-weight: 600; }
</style>
@endpush

@push('after-script')
<script>
    const table:MODEL_STUDLY = $('#:TABLE_ID').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route(':ROUTE_SLUG.table') }}",
        columns: [
:COLUMNS_JS
        ],
    });

    const modal:MODEL_STUDLY = $('#:MODAL_ID');
    const form:MODEL_STUDLY = $('#:FORM_ID');
    const methodInput:MODEL_STUDLY = $('#:METHOD_INPUT_ID');
    const idInput:MODEL_STUDLY = $('#:RESOURCE_ID_FIELD');
    const btnAdd:MODEL_STUDLY = $('#:BTN_ID');
    const storeUrl:MODEL_STUDLY = "{{ route(':ROUTE_SLUG.store') }}";
    const showUrl:MODEL_STUDLY = "{{ route(':ROUTE_SLUG.show', '__ID__') }}";
    const updateUrl:MODEL_STUDLY = "{{ route(':ROUTE_SLUG.update', '__ID__') }}";
    const deleteUrl:MODEL_STUDLY = "{{ route(':ROUTE_SLUG.destroy', '__ID__') }}";
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': csrfToken }
    });

    function resetForm:MODEL_STUDLY() {
        form:MODEL_STUDLY[0].reset();
        methodInput:MODEL_STUDLY.val('POST');
        idInput:MODEL_STUDLY.val('');
        form:MODEL_STUDLY.find('.is-invalid').removeClass('is-invalid');
        form:MODEL_STUDLY.find('.invalid-feedback').remove();
        modal:MODEL_STUDLY.find('.modal-title').text('Tambah :SINGULAR');
    }

    function fillForm:MODEL_STUDLY(data) {
:FILL_FIELDS_JS
    }

    function handleErrors:MODEL_STUDLY(errors) {
        Object.entries(errors).forEach(function ([key, messages]) {
            const inputs = form:MODEL_STUDLY.find('[name="' + key + '"]');
            if (!inputs.length) {
                return;
            }
            let input = inputs.first();
            if (input.attr('type') === 'hidden' && inputs.length > 1) {
                input = inputs.not('[type="hidden"]').first();
            }
            const container = input.closest(input.hasClass('form-check-input') ? '.form-check' : '.mb-3');
            input.addClass('is-invalid');
            container.append('<div class="invalid-feedback">' + messages[0] + '</div>');
        });
    }

    btnAdd:MODEL_STUDLY.on('click', function () {
        resetForm:MODEL_STUDLY();
        modal:MODEL_STUDLY.modal('show');
    });

    modal:MODEL_STUDLY.on('hidden.bs.modal', function () {
        resetForm:MODEL_STUDLY();
    });

    form:MODEL_STUDLY.on('submit', function (e) {
        e.preventDefault();
        form:MODEL_STUDLY.find('.is-invalid').removeClass('is-invalid');
        form:MODEL_STUDLY.find('.invalid-feedback').remove();

        const id = idInput:MODEL_STUDLY.val();
        const url = id ? updateUrl:MODEL_STUDLY.replace('__ID__', id) : storeUrl:MODEL_STUDLY;

        if (id) {
            methodInput:MODEL_STUDLY.val('PUT');
        } else {
            methodInput:MODEL_STUDLY.val('POST');
        }

        $.ajax({
            url: url,
            method: 'POST',
            data: form:MODEL_STUDLY.serialize(),
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: response.message || 'Berhasil',
                    timer: 1500,
                    showConfirmButton: false
                });
                modal:MODEL_STUDLY.modal('hide');
                table:MODEL_STUDLY.ajax.reload(null, false);
            },
            error: function (xhr) {
                const res = xhr.responseJSON || {};
                if (res.errors) {
                    handleErrors:MODEL_STUDLY(res.errors);
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: res.message || 'Terjadi kesalahan.'
                });
            }
        });
    });

    $('#:TABLE_ID').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        resetForm:MODEL_STUDLY();
        $.get(showUrl:MODEL_STUDLY.replace('__ID__', id))
            .done(function (response) {
                if (response.data) {
                    fillForm:MODEL_STUDLY(response.data);
                    idInput:MODEL_STUDLY.val(id);
                    methodInput:MODEL_STUDLY.val('PUT');
                    modal:MODEL_STUDLY.find('.modal-title').text('Edit :SINGULAR');
                    modal:MODEL_STUDLY.modal('show');
                }
            })
            .fail(function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Tidak dapat mengambil data.'
                });
            });
    });

    $('#:TABLE_ID').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus data ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: deleteUrl:MODEL_STUDLY.replace('__ID__', id),
                method: 'POST',
                data: { _method: 'DELETE' },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: response.message || 'Data dihapus',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    table:MODEL_STUDLY.ajax.reload(null, false);
                },
                error: function (xhr) {
                    const res = xhr.responseJSON || {};
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: res.message || 'Tidak dapat menghapus data.'
                    });
                }
            });
        });
    });
</script>
@endpush
BLADE;

        $indexContent = strtr($template, [
            ':TITLE' => $title,
            ':SINGULAR' => $singularTitle,
            ':BTN_ID' => $btnAddId,
            ':TABLE_ID' => $tableId,
            ':TABLE_HEADERS' => $tableHeaders,
            ':MODAL_ID' => $modalId,
            ':MODAL_LABEL' => $modalLabelId,
            ':FORM_ID' => $formId,
            ':METHOD_INPUT_ID' => $methodInputId,
            ':RESOURCE_ID_FIELD' => $resourceIdField,
            ':FORM_FIELDS' => $formFieldsHtml,
            ':MODEL_STUDLY' => $model,
            ':ROUTE_SLUG' => $routeSlug,
            ':COLUMNS_JS' => $columnsJs,
            ':FILL_FIELDS_JS' => $fillFieldsJs,
        ]);

        $indexPath = $directory . DIRECTORY_SEPARATOR . 'index.blade.php';
        File::put($indexPath, $indexContent);

        return ['index' => $indexPath];
    }

    protected function buildNormalizer(array $fields, string $variableName): string
    {
        $lines = [];
        $var = '$' . $variableName;

        foreach ($fields as $field) {
            $name = $field['name'];
            $nullable = $field['nullable'];

            switch ($field['type']) {
                case 'boolean':
                    $lines[] = "        {$var}['{$name}'] = !empty({$var}['{$name}']);";
                    break;
                case 'integer':
                case 'unsignedInteger':
                case 'bigInteger':
                case 'tinyInteger':
                    $lines[] = "        if (array_key_exists('{$name}', {$var})) {";
                    $lines[] = "            {$var}['{$name}'] = {$var}['{$name}'] === '' ? null : (int) {$var}['{$name}'];";
                    $lines[] = '        }';
                    break;
                case 'decimal':
                case 'float':
                case 'double':
                    $lines[] = "        if (array_key_exists('{$name}', {$var})) {";
                    $lines[] = "            {$var}['{$name}'] = {$var}['{$name}'] === '' ? null : (float) {$var}['{$name}'];";
                    $lines[] = '        }';
                    break;
                case 'json':
                    $lines[] = "        if (array_key_exists('{$name}', {$var})) {";
                    $lines[] = "            if ({$var}['{$name}'] === '' || {$var}['{$name}'] === null) {";
                    $lines[] = "                {$var}['{$name}'] = null;";
                    $lines[] = '            } else {';
                    $lines[] = "                \$decoded = json_decode({$var}['{$name}'], true);";
                    $lines[] = "                {$var}['{$name}'] = json_last_error() === JSON_ERROR_NONE ? \$decoded : null;";
                    $lines[] = '            }';
                    $lines[] = '        }';
                    break;
                default:
                    if ($nullable) {
                        $lines[] = "        if (array_key_exists('{$name}', {$var}) && {$var}['{$name}'] === '') {";
                        $lines[] = "            {$var}['{$name}'] = null;";
                        $lines[] = '        }';
                    }
                    break;
            }
        }

        return empty($lines) ? '' : "\n" . implode("\n", $lines) . "\n";
    }

    protected function appendRoutes(string $controller, string $routeSlug, string $resource): void
    {
        $routePath = base_path('routes/web.php');
        if (!File::exists($routePath)) {
            return;
        }

        $contents = File::get($routePath);
        $useStatement = "use App\\Http\\Controllers\\{$controller};";

        if (!Str::contains($contents, $useStatement)) {
            $lines = explode(PHP_EOL, $contents);
            $insertIndex = null;
            foreach ($lines as $index => $line) {
                $trimmed = trim($line);
                if (Str::startsWith($trimmed, 'use ') && Str::contains($trimmed, 'App\\Http\\Controllers')) {
                    $insertIndex = $index;
                }
            }

            if ($insertIndex === null) {
                array_splice($lines, 1, 0, $useStatement);
            } else {
                array_splice($lines, $insertIndex + 1, 0, $useStatement);
            }

            $contents = implode(PHP_EOL, $lines);
        }

        if (Str::contains($contents, "Route::prefix('{$routeSlug}')")) {
            File::put($routePath, $contents);
            return;
        }

        $modelVar = Str::camel($resource);
        $routeParameter = '{' . $modelVar . '}';

        $routeGroup = <<<PHP

        Route::prefix('{$routeSlug}')->name('{$routeSlug}.')->group(function () {
            Route::get('/', [{$controller}::class, 'index'])->name('index');
            Route::get('/table', [{$controller}::class, 'table'])->name('table');
            Route::post('/', [{$controller}::class, 'store'])->name('store');
            Route::get('/{$routeParameter}', [{$controller}::class, 'show'])->name('show');
            Route::put('/{$routeParameter}', [{$controller}::class, 'update'])->name('update');
            Route::delete('/{$routeParameter}', [{$controller}::class, 'destroy'])->name('destroy');
        });
PHP;

        $generatorMarker = "        Route::get('/generator', [ScaffoldController::class, 'index'])->name('generator.index');";

        if (Str::contains($contents, $generatorMarker)) {
            $contents = Str::replaceFirst($generatorMarker, rtrim($routeGroup) . PHP_EOL . PHP_EOL . $generatorMarker, $contents);
        } else {
            $closingMarker = "    });\n});";
            if (Str::contains($contents, $closingMarker)) {
                $contents = Str::replaceFirst($closingMarker, rtrim($routeGroup) . PHP_EOL . PHP_EOL . $closingMarker, $contents);
            } else {
                $contents .= PHP_EOL . trim($routeGroup) . PHP_EOL;
            }
        }

        File::put($routePath, $contents);
    }

    protected function createMenuItem(array $payload, string $routeSlug, string $resource): void
    {
        $menuCode = Str::slug($routeSlug);
        $menuName = Str::headline(Str::pluralStudly($resource));
        $menuParent = $payload['menu_parent'] ?? null;
        $icon = $payload['menu_icon'] ?? 'bi bi-circle';
        $sort = $payload['menu_sort'] ?? 0;

        $menu = Menu::withTrashed()->updateOrCreate(
            ['code' => $menuCode],
            [
                'name' => $menuName,
                'url' => $routeSlug,
                'icon' => $icon,
                'main_menu' => $menuParent ?: null,
                'menu_hassub' => 0,
                'sort' => $sort,
                'active' => 1,
            ]
        );

        if ($menu->trashed()) {
            $menu->restore();
        }

        $roleIds = Role::query()->pluck('id');
        foreach ($roleIds as $roleId) {
            RoleMenu::firstOrCreate([
                'role_id' => $roleId,
                'menu_id' => $menu->id,
            ]);
        }
    }

    protected function hasDefault(array $field): bool
    {
        return array_key_exists('default', $field) && $field['default'] !== null;
    }

    protected function formatDefault(array $field): string
    {
        $value = $this->normalizeDefaultValue($field['default'], $field['type']);

        return var_export($value, true);
    }

    protected function normalizeDefaultValue($value, string $type)
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,
            'integer', 'unsignedInteger', 'bigInteger', 'tinyInteger' => (int) $value,
            'decimal', 'float', 'double' => (float) $value,
            default => $value,
        };
    }
}
