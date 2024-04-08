@extends('master');
@section('content')
    ;
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="row align-items-center my-4">
                        <div class="col">
                            <h2 class="h3 mb-0 page-title">Users</h2>
                        </div>
                        <div class="col-auto">
                            <!-- <button type="button" class="btn btn-secondary"><span class="fe fe-trash fe-12 mr-2"></span>Delete</button> -->
                            <a href="{{ route('userindex') }}"> <button type="button" class="btn btn-primary"><span
                                        class="fe fe-filter fe-12 mr-2"></span>Create</button></a>
                        </div>
                    </div>
                    <!-- table -->
                    <div class="card shadow">
                        <div class="card-body">
                            <table class="table table-borderless table-hover" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="text-dark">ID</th>
                                        <th class="text-dark">name</th>
                                        <th class="text-dark">email</th>
                                        <th class="text-dark">Address</th>
                                        {{-- <th class="text-dark">Lat</th>
                                        <th class="text-dark">Lng</th> --}}
                                        {{-- <th class="text-dark">Registration Date</th> --}}
                                        <th class="text-dark">Avatar</th>
                                        <th class="text-dark">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($data as $key=>$user)
                                        <tr>
                                            <td>
                                                {{ ++$key}}
                                            </td>
                                            <td>
                                                {{ $user->name }}
                                            </td>
                                            <td>
                                                {{ $user->email }}
                                            </td>
                                            <td>
                                                {{ Str::substr($user->address,0, 15) }}{{strlen($user->address) > 15 ? ' ...' : ''}}
                                            </td>
                                            {{-- <td>
                                                {{ $user->lat }}
                                            </td>
                                            <td>
                                                {{ $user->lng }}
                                            </td> --}}
                                            {{-- <td>
                                                {{ isset($user->created_at) ? $user->created_at->format('Y-m-d') : '' }}
                                            </td> --}}
                                            <td>
                                                <img src="{{ asset('profiles/' . $user->profile_image) }}" height="50px"
                                                    width="50px">

                                            </td>

                                            <td>
                                                <a href="{{ route('user.del', $user->id) }}"> <button type="button"
                                                        class="btn btn-info btn-sm"><span class=""></span>Delete</button></a>
                                                <a href="{{ route('users_edit', $user->id) }}"> <button type="button"
                                                        class="btn btn-primary btn-sm"><span
                                                            class=""></span>Edit</button></a>
                                                @if ($user->status == 1)
                                                    <a href="{{ route('update_status', [$user->id, 'status_code' => 0]) }}">
                                                        <button type="button" class="btn btn-danger btn-sm"><span
                                                                class=""></span>Block</button></a>
                                                @else
                                                    <a href="{{ route('update_status', [$user->id, 'status_code' => 1]) }}">
                                                        <button type="button" class="btn btn-success"><span
                                                                class=""></span>Unblock</button></a>
                                                @endif

                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- .col-12 -->
            </div> <!-- .row -->
        </div> <!-- .container-fluid -->
    </main> <!-- main -->
    </div> <!-- .wrapper -->
@endsection
