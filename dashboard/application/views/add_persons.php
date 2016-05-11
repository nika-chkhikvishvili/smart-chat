<script type="text/javascript">
    $(document).ready(function () {
        $('td.editable-col').on('focusout', function () {
            data = {};
            data['val'] = $(this).text();
            data['id'] = $(this).parent('tr').attr('data-row-id');
            data['index'] = $(this).attr('col-index');
            if ($(this).attr('oldVal') === data['val'])
                return false;

            if (confirm('განვაახლოთ მონაცემები ?')) {
                $.ajax({
                    type: "POST",
                    url: "http://localhost/chat/dashboard/update_institution",
                    cache: false,
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        //$("#loading").hide();
                        if (response.status) {
                            $.Notification.notify('success', 'top center', 'ყურადღება', response.msg);
                            setTimeout(function () {
                                window.location.reload(1);
                            }, 3000);
                        } else {
                            $.Notification.notify('success', 'top center', 'ყურადღება', response.msg);
                            setTimeout(function () {
                                window.location.reload(1);
                            }, 3000);
                        }
                    }
                });
            }
        });
        // delete the entry once we have confirmed that it should be deleted
        $('.delete').click(function () {
            data = {};
            data['id'] = $(this).parent('tr').attr('data-row-id');
            var parent = $(this).closest('tr');
            if (confirm('დარწმუნებული ხართ რომ გინდათ უწყების წაშლა?')) {
                $.ajax({
                    type: "POST",
                    url: "http://localhost/chat/dashboard/delete_institution",
                    cache: false,
                    data: data,
                    dataType: "json",
                    beforeSend: function () {
                        parent.animate({'backgroundColor': '#fb6c6c'}, 300);
                    },
                    success: function (response) {

                        //$("#loading").hide();
                        if (response.status) {
                            $.Notification.notify('success', 'top center', 'ყურადღება', response.msg);
                            setTimeout(function () {
                                window.location.reload(1);
                            }, 3000);
                        } else {
                            $.Notification.notify('success', 'top center', 'ყურადღება', response.msg);
                            setTimeout(function () {
                                window.location.reload(1);
                            }, 3000);
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
                        <a href="<?= base_url(); ?>" class="btn btn-info waves-effect waves-light m-b-5" id="sa-params">მომხმარებლის
                            მართვა</a>
                    </div>
                </div>
            </div>


        </div>
        <!-- Start Widget -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">მომხმარებლის დამატება</h3></div>
                    <div class="panel-body">
                        <div class="form">
                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="get" action="#"
                                  novalidate="novalidate">
                                <div class="form-group">
                                    <label for="cname" class="control-label col-lg-2">სახელი (სავალდებულო)</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="cname" name="first_name" type="text" required=""
                                               aria-required="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cname" class="control-label col-lg-2">გვარი (სავალდებულო)</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="cname" name="last_name" type="text" required=""
                                               aria-required="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cname" class="control-label col-lg-2">მეტსახელი (სავალდებულო)</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="cname" name="nickname" type="text" required=""
                                               aria-required="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cemail" class="control-label col-lg-2">ელ-ფოსტა (სავალდებულო)</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="cemail" type="person_mail" name="email"
                                               required="" aria-required="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="curl" class="control-label col-lg-2">დაბადების თარიღი</label>
                                    <div class="col-lg-10">
                                        <input type="text" placeholder="" name="birthday" data-mask="9999-99-99"
                                               class="form-control">
                                        <span class="help-inline">წელი-თვე-დღე</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="curl" class="control-label col-lg-2">ტელეფონის ნომერი</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="curl" type="text" name="phone">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="ccomment" class="control-label col-lg-2"></label>
                                    <div class="col-lg-10">
                                        <div class="panel-heading">
                                        </div>
                                        <!-- სისტემური პარამეტრები -->
                                        <div class="col-md-6">
                                            <div class="panel panel-border  panel-danger">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">მონიშნეთ მომხმარებლის სისტემური
                                                        უფლებები</h3>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="checkbox checkbox-danger checkbox-circle">
                                                        <input id="checkbox-12" type="checkbox" checked="checked">
                                                        <label for="checkbox-12">
                                                            პირადი საუბრის ისტორია
                                                        </label>
                                                    </div>

                                                    <div class="checkbox checkbox-danger checkbox-circle">
                                                        <input id="checkbox-13" type="checkbox">
                                                        <label for="checkbox-13">
                                                            სისტემაში ჩართული ყველა ოპერატორის საუბრის ისტორია
                                                        </label>
                                                    </div>

                                                    <div class="checkbox checkbox-danger checkbox-circle">
                                                        <input id="checkbox-14" type="checkbox">
                                                        <label for="checkbox-14">
                                                            Offline შეტყობინებების მიღება
                                                        </label>
                                                    </div>
                                                    <div class="checkbox checkbox-danger checkbox-circle">
                                                        <input id="checkbox-15" type="checkbox">
                                                        <label for="checkbox-15">
                                                            სისტემაში ჩართული ოპერატორების Live Chat ის ყურება
                                                        </label>
                                                    </div>
                                                    <div class="checkbox checkbox-danger checkbox-circle">
                                                        <input id="checkbox-16" type="checkbox">
                                                        <label for="checkbox-16">
                                                            Online მომხმარებლის დაბლოკვა
                                                        </label>
                                                    </div>
                                                    <div class="checkbox checkbox-danger checkbox-circle">
                                                        <input id="checkbox-17" type="checkbox">
                                                        <label for="checkbox-17">
                                                            სისტემაში ჩართული ოპერატორის საუბარში ჩართვა
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- სისტემური პარამეტრები -->
                                        <!-- საკურატორო სერვისები -->
                                        <div class="col-md-6">
                                            <div class="panel panel-border panel-info">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">მონიშნეთ მომხმარებლის საკურატორო
                                                        სერვისები</h3>
                                                </div>
                                                <div class="panel-body">
                                                    <?php
                                                    foreach ($get_sql_services as $services):
                                                        ?>
                                                        <div class="checkbox checkbox-info checkbox-circle">
                                                            <input
                                                                id="checkbox-<?php echo $services['category_services_id']; ?>"
                                                                type="checkbox">
                                                            <label
                                                                for="checkbox-<?php echo $services['category_services_id']; ?>">
                                                                <?php echo $services['service_name']; ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- საკურატორო სერვისები -->
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-primary waves-effect waves-light m-b-5" type="submit">
                                            მომხმარებლის დამატება
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div> <!-- .form -->

                    </div> <!-- panel-body -->

                </div> <!-- panel -->

            </div> <!-- col -->

        </div>
        <!-- end row -->

    </div> <!-- container -->

</div> <!-- content -->
