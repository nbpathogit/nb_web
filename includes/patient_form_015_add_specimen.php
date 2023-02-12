

<h4 align="center"><b>รายการส่งตรวจ</b></h4>

<div class=" <?= $isBorder ? "border" : "" ?>">



    <table class="table table-bordered" id="spcimen_list_table">
        <thead>
            <tr>
                <th >Patient Number</th>
                <th >description</th>
                <th >Price</th>
                <th >Remark/comment</th>
            </tr>
        </thead>
        <tbody id="spcimen_list_table_body">
            <?php foreach ($billings as $billing): ?>
                <tr>
                    <td ><?= $billing['number'] ?></td>
                    <td ><?= $billing['description'] ?></td>
                    <td ><?= $billing['cost'] ?></td>
                    <td ></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


</div>

<botton id="refresh_spcimen_list" class="btn btn-primary" >Refresh</botton>