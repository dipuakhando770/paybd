<?php 
    $item_created  = time_ago($item['created_at']);
    $item_status   = ticket_status_title($item['status']) ;
?>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <p class="text-info"><i class="fa fa-ticket"></i> Ticket #<?php echo $item['ids']; ?></p>
            </div>
            <div class="card-body">
                <div class="ticket-details">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td scope="row"><?=lan("Status")?></td><td><?php echo $item_status; ?></td></tr>
                            <tr>
                                <td scope="row"><?=lan("Name")?></td><td><?=current_user('first_name');?></td>
                            </tr>
                            <tr>
                                <td scope="row"><?=lan("Email")?></td><td><?=current_user('email');?></td>
                            </tr>
                            <tr>
                                <td scope="row"><?=lan("subject")?></td><td><?=$item['subject'];?></td>
                            </tr>

                            <tr>
                                <td scope="row"><?=lan("Created")?></td><td><?php echo $item_created; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php
        $redirect_url =current_url();
        $form_attributes = ['class' => 'card-body form actionForm m-t-20', 'data-redirect' => $redirect_url, 'method' => "POST"];
        $form_hidden = ['id' => @$item['id']];
    ?>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h3 class="h4 ticket-title"><?php echo $item['subject']; ?></h3>
            </div>
            <?php 
                if ($item['status'] != 'closed') {
            ?>
                <?php echo form_open('', $form_attributes, $form_hidden); ?>
                    <div class="form-group">
                        <label for="userinput8"><?=lan("Message")?></label>
                        <textarea rows="5" class="form-control plugin_editor" name="message" ></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-min-width mt-1 float-end"><?=lan("Submit")?></button>
                <?php echo form_close(); ?>
                <hr/>   
            <?php } ?>
            <div id="frame">
                <div class="content">
                    <div class="messages">
                        <ul class="p-l-0">
                            <?php
                            
                                if ($items_ticket_message) {
                                    foreach ($items_ticket_message as $key => $item_message) {
                                        echo show_item_ticket_message_detail($controller_name,$item_message);
                                    }
                                }
                            ?>
                            <?php
                                $item['message'] = $item['description'];
                                echo show_item_ticket_message_detail($controller_name,$item);
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
