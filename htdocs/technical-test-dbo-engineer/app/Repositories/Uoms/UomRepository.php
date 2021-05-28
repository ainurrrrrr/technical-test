<?php namespace App\Repositories\Uoms;

use App\Uom;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

class UomRepository
{

    public function GetAllUom($request_payload): array
    {
        $offset = $request_payload->first;
        $rows = $request_payload->rows;
        $filters = $request_payload->filters;
        $sort_by = ($request_payload->sortOrder == 1) ? 'asc' : 'desc';
        $sort_field = $this->setSortField($request_payload->sortField);

        $uoms = DB::table('uoms')
            ->select(
                'uuid',
                'name',
                'code'
            )
            ->whereNull('deleted_at')
            ->where(function ($query) use ($filters) {
                if (isset($filters->global)) {
                    $search = $filters->global;
                    $query->OrWhere('name', 'like', "%{$search}%")
                        ->OrWhere('code', 'like', "%{$search}%");
                }
            })
            ->where(function ($query) use ($filters) {
                if (isset($filters->name)) {
                    $name = $filters->name;
                    $query->OrWhere('name', 'like', "%{$name}%");
                }
            })
            ->where(function ($query) use ($filters) {
                if (isset($filters->code)) {
                    $code = $filters->code;
                    $query->OrWhere('code', 'like', "%{$code}%");
                }
            })
            ->orderBy($sort_field, $sort_by);

        $collect = $uoms->get()->toArray();
        $total_records = collect($collect)->count();

        $results = $uoms->skip($offset)->take($rows)->get()->toArray();

        return [
            'uoms' => $results,
            'total_records' => $total_records,
            'message' => 'Success get uom'
        ];
    }

    public function setSortField($sort_field): string
    {
        if ($sort_field != null && $sort_field != 'undefined' && $sort_field != '') {
            switch ($sort_field) {
                case 'name':
                    return 'name';
                case 'code':
                    return 'code';
                default:
                    return 'created_at';
            }
        }

        return 'created_at';

    }

    public function store($request_payload): Uom
    {
        $uom = new Uom();
        $uom->uuid = Uuid::generate(4)->string;
        $uom->name = $request_payload->name;
        $uom->code = $request_payload->code;
        $uom->save();

        return $uom;
    }

    public function show($uuid)
    {
        return DB::table('uoms')
            ->select('uuid', 'name', 'code')
            ->where('uuid', $uuid)
            ->whereNull('deleted_at')
            ->first();
    }

    public function update($request_payload)
    {
        return Uom::where('uuid', $request_payload->uuid)
            ->update([
                'name' => $request_payload->name,
                'code' => $request_payload->code
            ]);
    }

    public function destroy($request_payload)
    {
        return Uom::where('uuid', $request_payload->uuid)
            ->delete();
    }
}
