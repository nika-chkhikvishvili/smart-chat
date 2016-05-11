<script type="text/javascript">
$(document).ready(function(){
  $('td.editable-col').on('focusout', function() {
    data = {};
    data['val'] = $(this).text();
    data['id'] = $(this).parent('tr').attr('data-row-id');
    data['index'] = $(this).attr('col-index');
    if($(this).attr('oldVal') === data['val'])
    return false;

    if(confirm('განვაახლოთ მონაცემები ?'))
         {
          $.ajax({
          type: "POST",
          url: "http://localhost/chat/dashboard/update_institution",
          cache:false,
          data: data,
          dataType: "json",
          success: function(response)
          {
            //$("#loading").hide();
            if(response.status) {
              $.Notification.notify('success','top center', 'ყურადღება', response.msg);
              setTimeout(function(){window.location.reload(1); }, 3000);
            } else {
              $.Notification.notify('success','top center', 'ყურადღება', response.msg);
              setTimeout(function(){window.location.reload(1); }, 3000);
            }
          }
        });
        }
    });
     // delete the entry once we have confirmed that it should be deleted
    $('.delete').click(function() {
    data = {};
    data['id'] = $(this).parent('tr').attr('data-row-id');
        var parent = $(this).closest('tr');
         if(confirm('დარწმუნებული ხართ რომ გინდათ უწყების წაშლა?'))
         {
         $.ajax({
            type: "POST",
              url: "http://localhost/chat/dashboard/delete_institution",
              cache:false,
              data: data,
              dataType: "json",
            beforeSend: function() {
                parent.animate({'backgroundColor':'#fb6c6c'},300);
            },
            success: function(response) {

            //$("#loading").hide();
                if(response.status) {
                  $.Notification.notify('success','top center', 'ყურადღება', response.msg);
                  setTimeout(function(){window.location.reload(1); }, 3000);
                } else {
                   $.Notification.notify('success','top center', 'ყურადღება', response.msg);
                   setTimeout(function(){window.location.reload(1); }, 3000);
                }
            }
        });
         }

    });

});
</script>

<div class="content">
    <div class="container">
    <div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">მომხმარებლის მართვა</h3>
            </div>
            <div class="panel-body">
                <a href="<?=base_url();?>dashboard/add_person" class="btn btn-info waves-effect waves-light m-b-5"  id="sa-params">მომხმარებლის დამატება</a>
            </div>
        </div>
    </div>
     <!-- modal info -->
    <div class="md-modal md-effect-6" id="modal-6">

        <div class="md-content">
            <h3>ინფორმაცია</h3>
            <div>
                <p>ნატალია აბაშმაძეს საკურატორო სერვისები:</p>
                <ul>
                    <li><strong>საარქივო სერვისები</strong></li>
                    <li><strong>ID ბარათი</strong> </li>
                    <li><strong>ბიზნესი</strong> </li>
                </ul>
                <button class="md-close btn btn-primary waves-effect waves-light">დახურვა</button>
            </div>
        </div>
     </div>
     <!-- modal info -->
        <div class="md-modal md-effect-6" id="modal-7">

        <div class="md-content">
            <h3>ინფორმაცია</h3>
            <div>
                 <div class="tab-pane" id="profile-2">
                  <!-- Personal-Information -->
                  <div class="panel panel-default panel-fill">

                    <div class="panel-body">
                        <div class="timeline-2">
                        <div class="time-item">
                            <div class="item-info">
                                <div class="text-muted">09:00:53</div>
                                <p><strong>სისტემაში ავტორიზაცია </strong></p>
                            </div>
                        </div>

                        <div class="time-item">
                            <div class="item-info">
                                <div class="text-muted">30 minutes ago</div>
                                <p><a href="#" class="text-info">Lorem</a> commented your post.</p>

                            </div>
                        </div>

                        <div class="time-item">
                            <div class="item-info">
                                <div class="text-muted">59 minutes ago</div>
                                <p><a href="#" class="text-info">Jessi</a> attended a meeting with<a href="#" class="text-success">John Doe</a>.</p>

                            </div>
                        </div>
                    </div>

                    </div>
                </div>
                <!-- Personal-Information -->
                </div>

                <button class="md-close btn btn-primary waves-effect waves-light">დახურვა</button>
            </div>
        </div>
     </div>
      <!-- end of modal info -->
        <!-- Start Widget -->
         <div class="row">
        <div class="col-sm-6 col-lg-4">
                <div class="panel">
                    <div class="panel-body">
                        <div class="media-main">
                            <a class="pull-left" href="#">
                                <img class="thumb-lg img-circle" src="<?=base_url();?>assets/images/users/girl.png" alt="">
                            </a>

                            <div class="info">
                                <h4>ნატალია აბაშმაძე</h4>
                                <p class="text-muted">ადმინისტრატორი</p>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr>
                        <ul class="social-links list-inline">
                            <li>
                                <a title="" data-placement="right" data-toggle="tooltip" href="javascript:;" class="tooltips md-trigger waves-effect waves-light" data-modal="modal-6">
                                <i class="fa  fa-info-circle"></i></a>
                            </li>
                             <li>
                                <a title="" data-placement="right" data-toggle="tooltip" href="javascript:;" class="tooltips md-trigger waves-effect waves-light" data-modal="modal-7">
                                <i class="fa fa-history"></i></a>
                            </li>

                        </ul>
                    </div> <!-- panel-body -->
                </div> <!-- panel -->
            </div> <!-- end col -->
             <div class="col-sm-6 col-lg-4">
                <div class="panel">
                    <div class="panel-body">
                        <div class="media-main">
                            <a class="pull-left" href="#">
                                <img class="thumb-lg img-circle" src="<?=base_url();?>assets/images/users/girl.png" alt="">
                            </a>
                            <div class="pull-right btn-group-sm">
                                <a href="#" class="btn btn-success waves-effect waves-light tooltips" data-placement="top" data-toggle="tooltip" data-original-title="რედაქტირება">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a href="#" class="btn btn-danger waves-effect waves-light tooltips" data-placement="top" data-toggle="tooltip" data-original-title="ანგარიშის გაუქმება">
                                    <i class="fa fa-close"></i>
                                </a>
                            </div>
                            <div class="info">
                                <h4>ნატალია აბაშმაძე</h4>
                                <p class="text-muted">ოპერატორი</p>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr>
                        <ul class="social-links list-inline">
                            <li>
                                <a title="" data-placement="right" data-toggle="tooltip" class="tooltips" href="#" data-original-title="საკურატორო სერვისები">
                                <i class="fa  fa-info-circle"></i></a>
                            </li>
                             <li>
                                <a title="" data-placement="right" data-toggle="tooltip" class="tooltips" href="#" data-original-title="მომხმარებლის ისტორია">
                                <i class="fa fa-history"></i></a>
                            </li>

                        </ul>
                    </div> <!-- panel-body -->
                </div> <!-- panel -->
            </div> <!-- end col -->
            </div> <!-- col -->
              <div class="row">
            <div class="col-sm-12">
                <ul class="pagination pull-right">
                    <li>
                      <a href="#" aria-label="Previous">
                        <i class="fa fa-angle-left"></i>
                      </a>
                    </li>
                    <li><a href="#">1</a></li>
                    <li class="active"><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li class="disabled"><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li>
                      <a href="#" aria-label="Next">
                        <i class="fa fa-angle-right"></i>
                      </a>
                    </li>
                </ul>
            </div>
        </div>
        </div>
        <!-- end row -->

    </div> <!-- container -->

</div> <!-- content -->
