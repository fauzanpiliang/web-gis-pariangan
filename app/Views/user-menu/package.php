<?= $this->extend('layout/template.php') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card p-2 shadow-sm">
        <div class="card-header text-center card-title  mb-2">LIST TOURISM PACKAGE</div>
        <div class="card-body">
            <div class="row d-flex">
                <?php foreach ($objectData as $data) : ?>
                    <div class="col-md-12">
                        <div class="card mb-3 shadow-sm">
                            <div class="row g-0">
                                <div class="col-md-4 p-2">
                                    <a class="hover-efek" role="button" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $data->id ?>">
                                        <img src="<?= base_url('media/photos') . '/' . $data->url ?>" class="img-fluid rounded-start" alt="...">
                                    </a>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $data->name; ?></h5>
                                        <?php if (isset($data->description)) : ?>
                                            <p class="card-text"><?= $data->description; ?></p>
                                        <?php endif; ?>
                                        <?php if (isset($data->price)) : ?>
                                            <p class="card-text"><small class="text-muted"><?= $data->price; ?> IDR</small></p>
                                        <?php endif; ?>

                                    </div>
                                    <div class="card-footer text-end" style="border: none;">
                                        <a role="button" class="btn btn-success" href="<?= base_url('package/detail') . '/' . $data->id; ?>">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal<?= $data->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"><?= $data->name; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img class="img-fluid w-100" src="<?= base_url('media/photos'); ?>/<?= $data->url; ?>" alt="Card image cap">
                                    <p class="card-text my-4" style="text-align: justify;">
                                        <?= $data->description; ?>
                                    </p>
                                </div>
                                <?php if (isset($data->price)) : ?>
                                    <div class="modal-footer">
                                        <span class="text-lg"><?= $data->price; ?> IDR</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>