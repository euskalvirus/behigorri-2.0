<html ng-app="prueba">
    <head>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}"></link>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
        <script src="{{ asset('js/prueba.js') }}"></script>
        <link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css" />
		<script src="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js"></script>

		<style type="text/css">
			.bootstrap-tagsinput {
    			width: 100%;
			}
			.label {
    			line-height: 2 !important;
			}
		</style>
        
    </head>
    <body ng-controller="pruebacontroller">
        <!--<div class="container-fluid">
            @{{greeting}}
        </div>  -->
        <div class="container">
            @yield('content')
        </div>
        
        
        
        
        <div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title">Delete Parmanently</h4>
	      </div>
	      <div class="modal-body">
	        <p>Are you sure about this ?</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	        <button type="button" class="btn btn-danger" id="confirm">Delete</button>
	      </div>
	    </div>
	  </div>
	</div>
	
	
           
        
    </body>
    <!-- Dialog show event handler -->
	<script type="text/javascript">
	  $('#confirmDelete').on('show.bs.modal', function (e) {
	      $message = $(e.relatedTarget).attr('data-message');
	      $(this).find('.modal-body p').text($message);
	      $title = $(e.relatedTarget).attr('data-title');
	      $(this).find('.modal-title').text($title);
	      // Pass form reference to modal for submission on yes/ok
	      var form = $(e.relatedTarget).closest('form');
	      $(this).find('.modal-footer #confirm').data('form', form);
	  });
	  <!-- Form confirm (yes/ok) handler, submits form -->
	  $('#confirmDelete').find('.modal-footer #confirm').on('click', function(){
	      $(this).data('form').submit();
	  });
	</script>
    
</html>