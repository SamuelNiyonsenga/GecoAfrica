<div class="col-lg-9">
                                    <div class="tab-content">
                                        <div id="inbox" class="tab-pane fade in animated zoomInDown custom-inbox-message shadow-reset active">
                                            <div class="mail-title inbox-bt-mg">
                                                <h2>Recipients</h2>
                                                
                                            </div>
                                            <div class="datatable-dashv1-list custom-datatable-overright">
                                                <div id="toolbar1">
                                                    <select class="form-control">
                                                        <option value="">Export Basic</option>
                                                        <option value="all">Export All</option>
                                                        <option value="selected">Export Selected</option>
                                                    </select>
                                                </div>
                                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar1">
                                                    <thead>
                                                        <tr>
                                                            <th data-field="state" data-checkbox="true"></th>
                                                            <th data-field="id">#</th>
                                                            <th data-field="recepient">Recipient</th>
                                                            <th data-field="status">Status</th>
                                                            <th data-field="date">received date</th>
                                                         
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        // select Staff receiver
                                                    
$SelectMyinbox= "SELECT `tb_communication_receiver`.*,`tb_members`.* FROM tb_communication_receiver LEFT JOIN tb_members ON(`tb_communication_receiver`.`tb_scr_receiver`=`tb_members`.`memberID`) WHERE `tb_communication_receiver`.`tb_scr_scID`=:sc_ID";	
$stmt = $db->prepare($SelectMyinbox);
$stmt->bindParam(':sc_ID', $_GET['sc_ID'], PDO::PARAM_STR);
$stmt->execute();
$countinbox = $stmt->rowCount();
$nn=0;
while ($row = $stmt->fetch()) {
    $receiver_fullname= $row['member_names'];
    if($row['tb_scr_receivedtime']==0){
        $user_read="<div class='btn-group project-list-ad-rd'> <button class='btn btn-white btn-xs'>unread</button></div>";
        $invit_readtime= $row['tb_scr_receivedtime']; 
    } 
    else{
        $user_read="<div class='btn-group project-list-ad'> <button class='btn btn-white btn-xs'>Read</button></div>"; 
        $invit_readtime= $row['tb_scr_receivedtime'];  
    }
  
   
    $nn+=1;
    echo" 
    <tr>
    <td></td>
    <td>$nn</td>
    <td> $receiver_fullname</td>
    <td> $user_read</td>
    <td> $invit_readtime</td>
</tr>";
}  
?>
                                                       
                                                       
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                         
                                    </div>
                                </div>