@extends('App')

@section('content-header', 'Access Rights')

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <x-col class="mb-3">
                        {{-- @if(Auth::user()->hasAccess('access-right-create'))
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Add</button>
                        @endif --}}
                    </x-col>

                    <x-col>
                        <x-table :thead="['Access Right', 'Action']">
                            @foreach($data as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->label }}</td>
                                    <td>
                                        @if(Auth::user()->hasAccess('access-right-read'))
                                            <a
                                                href="{{ route('access-right.show', $row->id) }}"
                                                class="btn btn-warning"
                                                title="Ubah"><i class="fas fa-pencil-alt"></i></a>
                                        @endif

                                        {{-- @if(Auth::user()->hasAccess('access-right-delete'))
                                            <form style=" display:inline!important;" method="POST" action="{{ route('access-right.destroy', $row->id) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Do you sure want to delete this data?')"
                                                    title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        @endif --}}
                                    </td>
                                </tr>
                            @endforeach
                        </x-table>
                    </x-col>

                    <x-col class="d-flex justify-content-end">
                        {{-- {{ $data->links() }} --}}
                    </x-col>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>

    <x-modal :title="'Add Data'" :id="'add-modal'" :size="'lg'">
        <form style="width: 100%" action="{{ route('access-right.store') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>
                <x-in-text
                    :label="'Name'"
                    :placeholder="'Insert access right name'"
                    :col="6"
                    :name="'label'"
                    :required="true">
                </x-in-text>
                <x-in-text
                    :label="'Notes'"
                    :placeholder="'Insert Notes'"
                    :col="6"
                    :name="'notes'"
                    :required="true">
                </x-in-text>
            </x-row>

            <x-col class="text-right">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </x-col>
        </form>
    </x-modal>

@endsection
