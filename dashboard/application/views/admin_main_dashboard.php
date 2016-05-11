<div class="content">
    <div class="container">
        <!-- Start Widget -->
        <div class="row">
            <div class="col-md-6 col-sm-6 col-lg-3">
                <div class="mini-stat clearfix bx-shadow bg-white">
                    <span class="mini-stat-icon bg-info"><i class="fa fa-usd"></i></span>
                    <div class="mini-stat-info text-right text-dark">
                        <span class="counter text-dark">15852</span>
                        Total Sales
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-3">
                <div class="mini-stat clearfix bx-shadow bg-white">
                    <span class="mini-stat-icon bg-warning"><i class="fa fa-shopping-cart"></i></span>
                    <div class="mini-stat-info text-right text-dark">
                        <span class="counter text-dark">956</span>
                        New Orders
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-3">
                <div class="mini-stat clearfix bx-shadow bg-white">
                    <span class="mini-stat-icon bg-pink"><i class="fa fa-user"></i></span>
                    <div class="mini-stat-info text-right text-dark">
                        <span class="counter text-dark">5210</span>
                        მომხამრებელი
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-3">
                <div class="mini-stat clearfix bx-shadow bg-white">
                    <span class="mini-stat-icon bg-success"><i class="fa fa-eye"></i></span>
                    <div class="mini-stat-info text-right text-dark">
                        <span class="counter text-dark">20544</span>
                        უნიკალური ვიზიტორი
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
           <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">ჩეთის მიმდინარეობა</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ოპერატორი</th>
                                                <th>მომხმარებელი</th>
                                                <th>სერვისი</th>
                                                <th>საუბრის დასაწყისი</th>
                                                <th>ქმედება</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($chats as $key=>$val){
                                                echo "<tr>
                                                <td> {$val->chat_id} </td>
                                                <td> მომხმრებელი </td>
                                                <td>{$val->first_name} {$val->last_name}</td>
                                                <td> {$val->name} </td>
                                                <td> {$val->add_date} </td>
                                                <td>
                                                    <a href='#' class='hidden on-editing save-row'><i class='fa fa-save'></i></a>
                                                    <a href='#' class='hidden on-editing cancel-row'><i class='fa fa-times'></i></a>
                                                    <a href='#' class='on-default edit-row'><i class='fa md-pageview' data-toggle='tooltip' data-placement='left' title='დათვალიერება'></i></a>&nbsp;&nbsp;
                                                    <a href='#' class='on-default edit-row'><i class='fa fa-play-circle-o' data-toggle='tooltip' data-placement='right' title='საუბარში ჩართვა'></i></a>
                                                </td>
                                            </tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end row -->

    </div> <!-- container -->
</div> <!-- content -->
