$(document).ready(function() {
    $('#dataTable').DataTable({
        responsive: true
    });
});
function decryptionPass(identifier) {
    id = $(identifier).data('data-id');
    action = $(identifier).data('button-action');
    $('#passModal').find('input[name="id"]').val(id);
    if(action == "edit" || action == "view" || action == "delete" || action == "downloadFile" )
    {
         $('#passModal').find('input[name="action"]').val(action);
         $('#passModal').modal('show');
    }
}
function submitDownload() {
    $('#passForm').submit();
    $('#passModal').find('input[name="password"]').val("");
    $('#passModal').modal('hide');
}
function fileValidation() {
  var maxSize="15728640"
  var fileInput = $('.upload-file');
  //var maxSize = fileInput.data('data-max-size');
  if(fileInput.get(0).files.length){
    var fileSize = fileInput.get(0).files[0].size; // in bytes
    if(fileSize>maxSize){
      alert('file size is more then' + maxSize + ' bytes');
      return false;
    }else{
      $('.upload-form' ).submit();
    }

  }
}
function showHide(sel){
  if(sel.selectedIndex>0){
    //$('#newPass').attr('style', 'display: block !important');
    $('#newPass').prop('required', true);
    $('#hidden_div').show();
  }else{
    //$('#newPass').prop('style', 'display: none !important;');
    $('#newPass').prop('required', false);
    $('#hidden_div').hide();
  }
}
