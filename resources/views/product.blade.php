@extends('master');
@section('content');
<main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
              <div class="row align-items-center my-4">
                <div class="col">
                  <h2 class="h3 mb-0 page-title">Products</h2>
                </div>
                <!-- <div class="col-auto"> -->
                  <!-- <button type="button" class="btn btn-secondary"><span class="fe fe-trash fe-12 mr-2"></span>Delete</button> -->
                <!-- <a href="{{ route('userindex') }}">  <button type="button" class="btn btn-primary"><span class="fe fe-filter fe-12 mr-2"></span>Create</button></a>
                </div> -->
              </div>
              <!-- table -->
              <div class="card shadow">
                <div class="card-body">
                  <table class="table table-borderless table-hover" id="myTable">
                  <thead>
                          <tr> 
                            <th>ID</th>
                            <th>Product_name</th>
                            <th>discription</th>
                            <th>Product_image</th> 
                            <th>Action</th>
                          </tr>
                        </thead>
                    <tbody>
                      @foreach($data as $key=>$user)
                          <tr>
                            <td>
                             {{++$key}}
                            </td>
                            <td>
                              {{$user->product_name}}
                            </td>
                            <td>
                              {{$user->description}}
                            </td>
                           
                            <td>
                              @if($user->images && isset($user->images->first()->image))
                              {{-- @foreach($user->images as $image) --}}
                              {{-- @dd($user->images->first()->image) --}}
                              <img src="{{ asset('products/' .$user->images->first()->image) }}" height="50px" width="50px">
                              {{-- @endforeach --}}
                              @else
                              <img src="{{ asset('images/logo.png') }}" height="50px" width="50px">
                              @endif
                            
                            </td>
                            
                            <td>
                            <a href="{{route('product.del', $user->id)}}">  <button type="button" class="btn btn-danger"><span class=""></span>Delete</button></a>
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
  
