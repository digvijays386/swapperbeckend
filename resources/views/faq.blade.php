@extends('master');
@section('content');
<main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
              <div class="row align-items-center my-4">
                <div class="col">
                  <h2 class="h3 mb-0 page-title">FAQ</h2>
                </div>
                <div class="col-auto">
                  <!-- <button type="button" class="btn btn-secondary"><span class="fe fe-trash fe-12 mr-2"></span>Delete</button> -->
   <a href="{{ route('faqindex') }}">  <button type="button" class="btn btn-primary"><span class="fe fe-filter fe-12 mr-2"></span>Create</button></a>
                
                </div>
              </div>
              <!-- table -->
              <div class="card shadow">
                <div class="card-body">
                  <table class="table table-borderless table-hover" id="myTable">
                  <thead>
                          <tr>
                           
                            <th>ID</th>
                            <th>Description</th>
                           <!-- <th>Icon</th> -->
                            <th>Action</th>

                           
                          </tr>
                        </thead>
                    <!-- <tbody>
                      @foreach($data as $user)
                      <tr>
                      <td>{{$user->id}}</td>
                      <td>{{$user->name}}</td>
                      <td>{{$user->email}}</td>
                      </tr>
                    </tbody> -->
                    <!-- @endforeach -->
                    <tbody>
                      @foreach($data as $user)
                          <tr>
                            <td>
                             {{$user->id}}
                            </td>
                            <td>
                              <!-- {{$user->description}} -->
                              {{trim($user->description)}}
                            </td>
                          
                    <!-- <td><img src="{{asset('storage/product/icon'.$user['icon'])}}" height="50px" width="50px"></td> -->
                          
                            <td>
                     <a href="{{route('faq.del', $user->id)}}">  <button type="button" class="btn btn-danger"><span class=""></span>Delete</button></a>
                     <a href="{{route('faq_edit', $user->id)}}">  <button type="button" class="btn btn-primary"><span class=""></span>Edit</button></a>
                             
                            </td>
                            <!-- <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="text-muted sr-only">Action</span>
                              </button>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{route('faq_edit', $user->id)}}">Edit</a>
                                <a class="dropdown-item" href="{{route('faq.del', $user->id)}}">Delete</a>
                              </div> -->
                            <!-- </td> -->
                          
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
  