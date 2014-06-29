
<div class="row">
    
    <div class="col-xs-7">
        <div class="row datum-row">
            <div class="col-xs-6">
                <strong>Description</strong>
            </div>
            <div class="col-xs-3 text-right">
                <strong>Last update</strong>
            </div>
            <div class="col-xs-3">
            </div>
        </div>

        @foreach ($sensitiveData as $datum)
        <div id="datum-{{{ $datum->id }}}" class="row datum-row" data-datum-id="{{{ $datum->id }}}">
            <div class="col-xs-6 js-datum-name">
                {{{ $datum->name }}}
            </div>
            <div class="col-xs-3 text-right">
                <small>{{{ $datum->updated_at }}} ({{{ $datum->user->username }}})</small>
            </div>
            <div class="col-xs-3">
                <span class="js-decrypt fa-stack fa-lg" data-datum-id="{{{ $datum->id }}}">
                    <i class="fa fa-circle-o fa-stack-2x"></i>
                    <i class="fa fa-unlock-alt fa-stack-1x"></i>
                </span>
                <span class="js-delete fa-stack fa-lg" data-datum-id="{{{ $datum->id }}}">
                    <i class="fa fa-circle-o fa-stack-2x"></i>
                    <i class="fa fa-times fa-stack-1x"></i>
                </span>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="col-xs-5">
        <div class="row">
            <button class="btn btn-success input-medium pull-right js-add-new">Add New</button>
        </div>
        <div class="row hidden js-new-form">
            <div class="span6">
                <form method="post">
                    <input name="id" type="hidden" class="js-form-id" />
                    <div class="controls controls-row">
                        <input name="name" type="text" class="span3 form-control js-form-name" placeholder="Description">
                    </div>
                    <div class="controls">
                        <textarea name="value" class="span6 form-control js-form-value" placeholder="Sensitive data" rows="5"></textarea>
                    </div>

                    <div class="controls">
                        <button type="submit" class="btn btn-primary input-medium pull-right">Send</button>
                        <button type="reset" class="btn btn-warning input-medium pull-right js-add-new-cancel">Cancel</button>
                    </div>
                </form>
            </div>
        </div>    
    </div>
    
</div>


<div class="modal fade js-decrypt-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Decrypt data</h4>
      </div>
      <div class="modal-body">
          <input type="hidden" name="id"/>
          <input type="password" name="password" placeholder="password" class="form-control"/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger js-submit" disabled="disabled">Decrypt Now</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade js-delete-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Delete data</h4>
      </div>
      <div class="modal-body">
          <input type="hidden" name="id"/>
          <input type="password" name="password" placeholder="password" class="form-control"/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger js-submit" disabled="disabled">Delete Now</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->