<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
       @if (Session::has('create'))
       <div class="alert-big alert alert-success alert-dismissible fade show" role="alert">
          <div class="alert-content">
             <h6 class='alert-heading'>Success</h6>
             <p>{{ Session::get('create') }}</p>
          </div>
       </div>
       <br>
       @endif
       @if (Session::has('update'))
       <div class="alert-big alert alert-info alert-dismissible fade show" role="alert">
          <div class="alert-content">
             <h6 class='alert-heading'>Update</h6>
             <p>{{ Session::get('update') }}</p>
          </div>
       </div>
       <br>
       @endif
       @if (Session::has('warning'))
       <div class="alert-big alert alert-warning alert-dismissible fade show" role="alert">
          <div class="alert-content">
             <h6 class='alert-heading'>Warning</h6>
             <p>{{ Session::get('warning') }}</p>
          </div>
       </div>
       <br>
       @endif
       @if (Session::has('delete'))
       <div class="alert-big alert alert-info alert-dismissible fade show" role="alert">
          <div class="alert-content">
             <h6 class='alert-heading'>Delete</h6>
             <p>{{ Session::get('delete') }}</p>
          </div>
       </div>
       <br>
       @endif
       @if (Session::has('exist'))
       <div class="alert-big alert alert-warning alert-dismissible fade show" role="alert">
          <div class="alert-content">
             <h6 class='alert-heading'>Exist</h6>
             <p>{{ Session::get('exist') }}</p>
          </div>
       </div>
       @endif
 
       @if (Session::has('not_found'))
       <div class="alert-big alert alert-warning alert-dismissible fade show" role="alert">
          <div class="alert-content">
             <h6 class='alert-heading'>Not Found</h6>
             <p>{{ Session::get('not_found') }}</p>
          </div>
       </div>
       <br>
       @endif
    </div>
 </div>