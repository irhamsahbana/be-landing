@extends('App')

@php
    // $hasAccessCreate = Auth::user()->hasAccess('course-master-create');
    // $hasAccessRead = Auth::user()->hasAccess('course-master-read');
    // $hasAccessUpdate = Auth::user()->hasAccess('course-master-update');
    // $hasAccessDelete = Auth::user()->hasAccess('course-master-delete');
@endphp

@section('content-header', 'Sign Up list')

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <x-col class="mb-3">
                        {{-- @if($hasAccessCreate)
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Tambah</button>
                        @endif --}}
                    </x-col>

                    <x-col>
                        <x-table :thead="['First Name', 'Last Name', 'Email', 'Phone', 'Industry', 'Message', 'Created At', 'Verified At'/*'Action'*/]">
                            @foreach($data as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->first_name }}</td>
                                    <td>{{ $row->last_name }}</td>
                                    <td>{{ $row->email }}</td>
                                    <td>{{ "{$row->country_code} {$row->phone}" }}</td>
                                    <td>{{ $row->industry->label }}</td>
                                    <td>{{ $row->message }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row->created_at)->timezone('Europe/London')->format('M d Y H:i:s') }}</td>
                                    <td>{{ $row->verified_at ? \Carbon\Carbon::parse($row->verified_at)->timezone('Europe/London')->format('M d Y H:i:s') : '-' }}</td>
                                    {{-- <td>
                                        @if($hasAccessRead)
                                            <a
                                                href="{{ route('course-master.show', $row->id) }}"
                                                class="btn btn-warning"
                                                title="Ubah"><i class="fas fa-pencil-alt"></i></a>
                                        @endif

                                        @if($hasAccessDelete)
                                            <form style=" display:inline!important;" method="POST" action="{{ route('course-master.destroy', $row->id) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                                    title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        @endif
                                    </td> --}}
                                </tr>
                            @endforeach
                        </x-table>
                    </x-col>

                    <x-col class="d-flex justify-content-end">
                        {{ $data->links() }}
                    </x-col>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>

    {{-- <x-modal :title="'Tambah Data'" :id="'add-modal'" :size="'lg'">
        <form style="width: 100%" action="{{ route('course-master.store') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>
                <x-in-text
                    :label="'Kode'"
                    :placeholder="'Masukkan Kode'"
                    :col="6"
                    :name="'ref_no'"
                    :required="true"></x-in-text>
                <x-in-text
                    :label="'Nama'"
                    :placeholder="'Masukkan Nama'"
                    :col="6"
                    :name="'name'"
                    :required="true"></x-in-text>
            </x-row>

            <x-col class="text-right">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </x-col>
        </form>
    </x-modal> --}}

@endsection
