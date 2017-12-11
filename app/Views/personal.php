<div class="container-fluid">
    <div class="row">
        <div class="col-md-offset-2 col-md-7">
            <div class="page-header">
                <h1 class="text-center">
                    <?= number_format($data['user']->balance, 2, '.', ' ') ?> <i class="fa fa-rub"
                                                                                 aria-hidden="true"></i>
                </h1>
            </div>
            <?php if (!empty($messageList['error'])) : ?>
                <?php foreach ($messageList['error'] as $error) : ?>
                    <div class="alert alert-danger">
                        <?= $error ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (!empty($messageList['success'])) : ?>
                <?php foreach ($messageList['success'] as $success) : ?>
                    <div class="alert alert-success">
                        <?= $success ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <form class="form-horizontal" role="form" method="post" action="/main/cashout">
                <div class="form-group">

                    <label for="inputEmail3" class="col-sm-2 control-label">
                        Sum
                    </label>
                    <div class="col-sm-10">
                        <input type="text" name="sum" class="form-control" id="inputEmail3"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">

                        <button type="submit" class="btn btn-default">
                            Cash out
                        </button>
                    </div>
                </div>
                <input type="hidden" name="csrf" value="<?= \App\Core\CSRF::get_csrf(); ?>"/>
            </form>
        </div>
    </div>
</div>