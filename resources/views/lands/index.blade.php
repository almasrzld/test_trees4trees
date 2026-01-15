@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    <h1 class="text-2xl font-semibold mb-6">
        Data Lahan (Hasil Merge GEKO & BHL)
    </h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="p-4 bg-white rounded shadow">
            <p class="text-sm text-gray-500">Total Lahan</p>
            <p class="text-xl font-bold">{{ $stats['total_lahan'] }}</p>
        </div>

        <div class="p-4 bg-white rounded shadow">
            <p class="text-sm text-gray-500">Total Petani</p>
            <p class="text-xl font-bold">{{ $stats['total_petani'] }}</p>
        </div>

        <div class="p-4 bg-white rounded shadow">
            <p class="text-sm text-gray-500">GEKO</p>
            <p class="text-xl font-bold text-green-600">
                {{ $stats['lahan_geko'] }}
            </p>
        </div>

        <div class="p-4 bg-white rounded shadow">
            <p class="text-sm text-gray-500">BHL</p>
            <p class="text-xl font-bold text-blue-600">
                {{ $stats['lahan_bhl'] }}
            </p>
        </div>
    </div>

    <form method="GET" action="{{ route('lands.index') }}"
        class="bg-white p-4 rounded shadow mb-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div>
                <label class="block text-sm font-medium mb-1">Desa</label>
                <select name="village"
                    class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Semua Desa</option>
                    @foreach ($villages as $v)
                        <option value="{{ $v }}" {{ request('village') == $v ? 'selected' : '' }}>
                            {{ $v }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Kecamatan</label>
                <select name="kecamatan"
                    class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Semua Kecamatan</option>
                    @foreach ($kecamatan as $k)
                        <option value="{{ $k }}" {{ request('kecamatan') == $k ? 'selected' : '' }}>
                            {{ $k }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Kota / Kabupaten</label>
                <select name="city"
                    class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Semua Kota</option>
                    @foreach ($cities as $c)
                        <option value="{{ $c }}" {{ request('city') == $c ? 'selected' : '' }}>
                            {{ $c }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button
                    class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('lands.index') }}"
                    class="bg-gray-200 px-4 py-2 rounded text-sm hover:bg-gray-300">
                    Reset
                </a>
            </div>

        </div>
    </form>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">No Lahan</th>
                    <th class="px-4 py-2">No Petani</th>
                    <th class="px-4 py-2">Desa</th>
                    <th class="px-4 py-2">Kecamatan</th>
                    <th class="px-4 py-2">Kota</th>
                    <th class="px-4 py-2">Provinsi</th>
                    <th class="px-4 py-2">Source</th>
                    <th class="px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $row)
                    <tr class="border-t {{ in_array($row->lahan_no, $duplicate)
                        ? 'bg-red-50 hover:bg-red-100'
                        : 'hover:bg-gray-50' }}">
                        <td class="px-4 py-2">{{ $row->id }}</td>
                        <td class="px-4 py-2">{{ $row->lahan_no }}</td>
                        <td class="px-4 py-2">{{ $row->farmer_no }}</td>
                        <td class="px-4 py-2">{{ $row->village }}</td>
                        <td class="px-4 py-2">{{ $row->kecamatan }}</td>
                        <td class="px-4 py-2">{{ $row->city }}</td>
                        <td class="px-4 py-2">{{ $row->province }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs font-medium
                                {{ $row->source_data == 'GEKO'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-blue-100 text-blue-700' }}">
                                {{ $row->source_data }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            @if ($row->status_merge === 'duplicated')
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    Duplicated
                                </span>

                            @elseif ($row->status_merge === 'existing')
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-slate-200 text-slate-700">
                                    Existing
                                </span>

                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                    New
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500">
                            Data tidak ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $data->withQueryString()->links() }}
    </div>

</div>
@endsection
