<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleMenu;
use App\Services\Scaffold\ScaffoldService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class ScaffoldController extends Controller
{
    public function __construct(private readonly ScaffoldService $service)
    {
    }

    public function index(): View
    {
        $menu = Menu::updateOrCreate(
            ['code' => 'generator'],
            [
                'name' => 'Generator CRUD',
                'url' => 'generator',
                'icon' => 'bi bi-lightning',
                'main_menu' => null,
                'menu_hassub' => 0,
                'sort' => 99,
                'active' => 1,
            ]
        );

        Role::query()->pluck('id')->each(function ($roleId) use ($menu) {
            RoleMenu::firstOrCreate([
                'role_id' => $roleId,
                'menu_id' => $menu->id,
            ]);
        });

        $menuOptions = $this->menuOptions();
        $fieldTypes = [
            'string' => 'String',
            'text' => 'Text',
            'longText' => 'Long Text',
            'integer' => 'Integer',
            'unsignedInteger' => 'Unsigned Integer',
            'bigInteger' => 'Big Integer',
            'tinyInteger' => 'Tiny Integer',
            'boolean' => 'Boolean',
            'decimal' => 'Decimal (12,2)',
            'float' => 'Float',
            'double' => 'Double',
            'date' => 'Date',
            'datetime' => 'Date Time',
            'time' => 'Time',
            'json' => 'JSON',
        ];

        return view('scaffold.index', compact('menuOptions', 'fieldTypes'));
    }

    public function store(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'resource_name' => ['required', 'string', 'max:100'],
            'table_name' => ['nullable', 'string', 'max:120'],
            'fields' => ['required', 'array', 'min:1'],
            'fields.*.name' => ['required', 'string', 'max:120'],
            'fields.*.type' => ['required', 'string', 'in:string,text,longText,integer,unsignedInteger,bigInteger,tinyInteger,boolean,decimal,float,double,date,datetime,time,json'],
            'fields.*.nullable' => ['nullable', 'integer', 'in:0,1'],
            'fields.*.unique' => ['nullable', 'integer', 'in:0,1'],
            'fields.*.default' => ['nullable', 'string'],
            'menu_parent' => ['nullable', 'string', 'exists:menus,id'],
            'menu_icon' => ['nullable', 'string', 'max:100'],
            'menu_sort' => ['nullable', 'integer'],
        ]);

        $payload['resource_name'] = Str::studly($payload['resource_name']);
        $payload['table_name'] = $payload['table_name']
            ? Str::snake($payload['table_name'])
            : Str::snake(Str::pluralStudly($payload['resource_name']));

        $fields = collect($payload['fields'])
            ->map(function (array $field) {
                $defaultRaw = $field['default'] ?? null;
                if (is_string($defaultRaw)) {
                    $trimmed = trim($defaultRaw);
                    $default = $trimmed === '' ? null : $trimmed;
                } else {
                    $default = $defaultRaw;
                }

                return [
                    'name' => Str::snake($field['name']),
                    'type' => $field['type'],
                    'nullable' => (int) ($field['nullable'] ?? 0) === 1,
                    'unique' => (int) ($field['unique'] ?? 0) === 1,
                    'default' => $default,
                ];
            })
            ->unique('name')
            ->values();

        if ($fields->isEmpty()) {
            return response()->json([
                'message' => 'At least one unique field is required.',
            ], 422);
        }

        $payload['fields'] = $fields->toArray();
        $payload['menu_parent'] = isset($payload['menu_parent']) && $payload['menu_parent'] !== ''
            ? $payload['menu_parent']
            : null;
        $payload['menu_icon'] = isset($payload['menu_icon']) && trim($payload['menu_icon']) !== ''
            ? trim($payload['menu_icon'])
            : 'bi bi-circle';
        $payload['menu_sort'] = isset($payload['menu_sort'])
            ? (int) $payload['menu_sort']
            : 0;

        if (Schema::hasTable($payload['table_name'])) {
            return response()->json([
                'message' => "Table {$payload['table_name']} already exists.",
            ], 422);
        }

        try {
            $result = $this->service->generate($payload);
        } catch (RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Scaffold created successfully.',
            'data' => $result,
        ]);
    }

    protected function menuOptions(): array
    {
        $menus = Menu::whereNull('main_menu')
            ->with('children.children')
            ->orderBy('sort')
            ->get();

        $options = [['id' => '', 'label' => '- Tidak Ada -']];

        $walk = function ($menus, string $prefix = '') use (&$walk, &$options) {
            foreach ($menus as $menu) {
                $options[] = [
                    'id' => $menu->id,
                    'label' => $prefix . $menu->name,
                ];

                if ($menu->children && $menu->children->isNotEmpty()) {
                    $walk($menu->children, $prefix . '-- ');
                }
            }
        };

        $walk($menus);

        return $options;
    }
}
