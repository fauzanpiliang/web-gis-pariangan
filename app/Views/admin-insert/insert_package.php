<?= $this->extend('layout/template.php') ?>
<?= $this->section('head') ?>
<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/filepond-plugin-media-preview@1.0.11/dist/filepond-plugin-media-preview.min.css">
<link rel="stylesheet" href="<?= base_url('assets/css/pages/form-element-select.css'); ?>">
<style>
    .filepond--root {
        width: 100%;
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- modal detail -->
<div class="modal fade text-left" id="modalPackage" tabindex="-1" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalHeader"> </h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-body" id="modalBody">

            </div>
            <div class="modal-footer" id="modalFooter">

            </div>
        </div>
    </div>
</div>
<section class="section">
    <form class="form form-horizontal" action="<?= base_url('manage_package/save_insert'); ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
        <div class="form-body">
            <div class="row">
                <script>
                    currentUrl = '<?= current_url(); ?>';
                </script>
                <!-- Object Detail Information -->
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-center"><?= $title; ?></h4>
                        </div>
                        <div class="card-body">

                            <div class="form-group mb-4">
                                <label for="name" class="mb-2">Tourism Package Name <span class="text-danger">*</span></label>
                                <input type="text" id="name" class="form-control" name="name" placeholder="Tourism Package Name" value="" required>
                            </div>
                            <div class="form-group mb-4">
                                <label for="date" class="mb-2">Date <span>( optional )</span></label>
                                <input type="date" id="date" class="form-control" name="date" placeholder="date" value="null">
                            </div>
                            <?php if (isset($homestayData)) : ?>
                                <fieldset class="form-group mb-4">
                                    <label for="id_homestay" class="mb-2">Homestay</label>
                                    <select class="form-select" id="id_homestay" name="id_homestay">
                                        <option value="" selected> </option>
                                        <?php if ($homestayData) : ?>
                                            <?php foreach ($homestayData as $homestay) : ?>
                                                <?php if ($edit && $homestay['id'] && $data['id_homestay']) : ?>
                                                    <option value="<?= $homestay['id'] ?>" <?= (esc($homestay['id']) == $data['id_homestay']) ? 'selected' : ''; ?>><?= $homestay['name']; ?></option>
                                                <?php else : ?>
                                                    <option value="<?= esc($homestay['id']); ?>"><?= esc($homestay['name']); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>

                                        <?php endif; ?>
                                    </select>
                                </fieldset>
                            <?php endif; ?>
                            <div class="form-group mb-4">
                                <label for="price" class="mb-2">Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp </span>
                                    <input type="number" id="price" class="form-control" name="price" placeholder="price" aria-label="price" aria-describedby="price" value="0" required>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="capacity" class="mb-2">Capacity <span class="text-danger">*</span></label>
                                <input type="text" id="capacity" class="form-control" name="capacity" placeholder="capacity" value="" required>
                            </div>
                            <div class="form-group mb-4">
                                <label for="contact_person" class="mb-2">Contact Person</label>
                                <input type="tel" id="contact_person" class="form-control" name="cp" placeholder="Contact Person" value="">
                            </div>
                            <!-- service package include -->
                            <div class="form-group mb-4">
                                <label for="service_package" class="mb-2">Service Package (include)</label>
                                <select class="choices form-select multiple-remove" multiple="multiple" id="service_package" name="service_package[]">
                                    <?php foreach ($serviceData as $service) : ?>

                                        <option value="<?= esc($service['id']); ?>"><?= esc($service['name']); ?></option>

                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- service package exclude -->
                            <div class="form-group mb-4">
                                <label for="service_package_exclude" class="mb-2">Service Package (exclude) </label>
                                <select class="choices form-select multiple-remove" multiple="multiple" id="service_package_exclude" name="service_package_exclude[]">
                                    <?php foreach ($serviceData as $service) : ?>
                                        <option value="<?= esc($service['id']); ?>"><?= esc($service['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group mb-4">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                            </div>
                            <div class="form-group mb-4">
                                <label for="gallery" class="form-label">Brosur url</label>
                                <input class="form-control" accept="image/*" type="file" name="gallery[]" id="gallery">
                            </div>

                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-center">Detail package</h4>
                            <input type="hidden" required id="checkDetailPackage">
                        </div>
                        <div class="card-body">
                            <button type="button" onclick="openPackageDayModal(`${noDay}`)" class="btn btn-outline-primary block" data-bs-toggle="modal" data-bs-target="#modalPackage"> New package day
                            </button>

                            <div class="p-4" id="package-day-container">
                                <?php $noDay = 1; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://cdn.jsdelivr.net/npm/filepond-plugin-media-preview@1.0.11/dist/filepond-plugin-media-preview.min.js"></script>
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="<?= base_url('assets/js/extensions/form-element-select.js'); ?>"></script>
<script>
    function checkRequired(event) {
        let checkDetailPackage = $('#checkDetailPackage').val()

        if (!checkDetailPackage) {
            event.preventDefault();
            Swal.fire('You dont have any activities, please add 1 at least', '', 'warning');
        }
    }

    function removeObject(noDay, noDetail, price) {
        $(`#${noDay}-${noDetail}`).remove()
        let current = $(`#lastNoDetail${noDay}`).val()
        $(`#lastNoDetail${noDay}`).val(current - 1)

        let currentPrice = parseInt($(`#price`).val())
        let finalPrice = currentPrice - parseInt(price)
        console.log("object price" + price)
        console.log("current price " + currentPrice)
        console.log("final price " + finalPrice)

        $(`#price`).val(finalPrice)

    }

    //open modal package day
    function openPackageDayModal(noDay) {
        $("#modalHeader").html(`New package day`)
        $("#modalBody").html(
            `<div class="form-group mb-4">
                <label for="package-day" class="mb-2">Day</label>
                <input type="text" value="${noDay}" id="package-day" class="form-control" name="description" placeholder="package day" readonly>
            </div>
            <div class="form-group mb-4">
                <label for="package-day-description" class="mb-2">Description</label>
                <input type="text" id="package-day-description" class="form-control" name="description" placeholder="package day description" required>
            </div>`
        )
        $("#modalFooter").html(
            `<button type="button" class="btn" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" onclick="addPackageDay()" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Save</span>
             </button>`
        )
    }

    let noDay = <?= $noDay ?>

    // add package day to container
    function addPackageDay() {
        let packageDayDescription = $("#package-day-description").val()
        $("#package-day-container").append(`
        <div class="border shadow-sm p-2 mb-2">
        <span> Day </span>  <input type="text" value="${noDay}" name="packageDetailData[${noDay}][day]"  class="d-block" readonly> 
        <span> Object count </span> <input disabled value="0" type="text" id="lastNoDetail${noDay}" class="d-block">
        <span> Description </span>  <input value="${packageDayDescription}" name="packageDetailData[${noDay}][packageDayDescription]" class="d-block" >  
        <br>
        <br>
        <a class="btn btn-outline-success btn-sm" onclick="openDetailPackageModal('${noDay}')" data-bs-toggle="modal" data-bs-target="#modalPackage"> <i class="fa fa-plus"> </i> </a>
        <table class="table table-border" id="table-day"> 
            <thead>
                <tr>
                    <th>Object code <span class="text-danger">*</span></th>
                    <th>Activity type</th>
                    <th>Activity price</th>
                    <th>Description <span class="text-danger">*</span></th>
                </tr>  
            </thead>
            <tbody id="body-detail-package-${noDay}">

            </tbody>     
        </table>
        </div>`)
        noDay++
    }

    function openDetailPackageModal(noDay) {
        $("#modalHeader").html(`Add Day ${noDay} Detail`)
        $("#modalBody").html(`
        <input type="text" id="detail-package-day" class="form-control" name="detail-package-day" value="${noDay}" readonly placeholder="object" required>
       
        <div class="form-group mb-4">
                    <label for="select-object" class="mb-2">Object</label>
                    <select class="form-select" onchange="addObjectValue(this.value)" required>
                                     <option >Pilih objek</option>
                                    <?php if ($objectData) : ?>
                                        <?php $no = 0; ?>       
                                        <?php foreach ($objectData as $object) : ?>
                                            
                                    <option value="<?= esc(json_encode($object)) ?>"> <?= $object->id ?> - <?= esc($object->name); ?></option>
                                        
                                            <?php $no++; ?>       
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                       
                                    <?php endif; ?>
                     </select>
        </div>
        <input id="detail-package-id-object" type="hidden" required>
        <input id="detail-package-price-object" type="hidden" type="number" value="0" required>
       
        <div class="form-group mb-4">
                    <label for="detail-package-description" class="mb-2">Description</label>
                    <input type="text" id="detail-package-description" class="form-control" name="detail-package-description" placeholder="Detail package description">
        </div>
        `)
        $("#modalFooter").html(
            `<button type="button" class="btn" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" onclick="saveDetailPackageDay(${noDay})" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Save</span>
            </button>`
        )
    }

    function addObjectValue(object) {
        let objectData = JSON.parse(object)
        let objectId = objectData.id
        let objectName = objectData.name
        $("#detail-package-id-object").val(objectId)
        $("#detail-package-description").val("Visit " + objectName)
        let objectPrice = objectData.price == null ? 0 : parseInt(objectData.price)
        $("#detail-package-price-object").val(objectPrice)
    }

    function saveDetailPackageDay(noDay) {
        //get data from modal input
        let noDetail = parseInt($(`#lastNoDetail${noDay}`).val())
        let object_price = parseInt($("#detail-package-price-object").val())
        let object_id = $("#detail-package-id-object").val()
        let activity_type = ""
        let activity_price = parseInt($('#detail-package-price-object').val())
        let description = $("#detail-package-description").val()

        if (object_id.substring(0, 1) == 'A') {
            activity_type = 'Atraksi'
        } else if (object_id.substring(0, 1) == 'C') {
            activity_type = 'Culinary Place'
        } else if (object_id.substring(0, 1) == 'S') {
            activity_type = 'Souvenir Place'
        } else if (object_id.substring(0, 1) == 'W') {
            activity_type = 'Worship Place'
        } else if (object_id.substring(0, 1) == 'H') {
            activity_type = 'Homestay'
        }
        $(`#body-detail-package-${noDay}`).append(`
        <tr id="${noDay}-${noDetail}"> 
          <td><input class="form-control" value="${object_id}" name="packageDetailData[${noDay}][detailPackage][${noDetail}][id_object]" required readonly></td>
          <td><input class="form-control" value="${activity_type}" name="packageDetailData[${noDay}][detailPackage][${noDetail}][activity_type]"
          readonly></td>
          <td><input class="form-control" value="${activity_price}" name="packageDetailData[${noDay}][detailPackage][${noDetail}][activity_price]"
          readonly></td>
          <td><input class="form-control" value="${description}" name="packageDetailData[${noDay}][detailPackage][${noDetail}][description]" required></td>
          <td><a class="btn btn-danger" onclick="removeObject('${noDay}','${ noDetail }','${object_price}')"> <i class="fa fa-x"></i> </a></td>
        </tr>     
        `)
        $(`#lastNoDetail${noDay}`).val(noDetail + 1)
        $('#checkDetailPackage').val('oke')
        // price counting
        let currentPrice = parseInt($('#price').val())
        let finalPrice = currentPrice + object_price
        console.log("current price : " + currentPrice)
        console.log("object price :" + object_price)
        console.log("final price : " + finalPrice)

        $('#price').val(finalPrice)
    }
</script>

<script>
    FilePond.registerPlugin(
        FilePondPluginFileValidateType,
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview,
        FilePondPluginImageResize,
        FilePondPluginMediaPreview,
    );

    // Get a reference to the file input element
    const photo = document.querySelector('input[id="gallery"]');

    // Create a FilePond instance
    const pond = FilePond.create(photo, {
        imageResizeTargetHeight: 720,
        imageResizeUpscale: false,
        credits: false,
    })

    pond.setOptions({
        server: {
            timeout: 3600000,
            process: {
                url: '<?= base_url("upload/photo") ?>',
                onload: (response) => {
                    console.log("processed:", response);
                    return response
                },
                onerror: (response) => {
                    console.log("error:", response);
                    return response
                },
            },
            revert: {
                url: '<?= base_url("upload/photo") ?>',
                onload: (response) => {
                    console.log("reverted:", response);
                    return response
                },
                onerror: (response) => {
                    console.log("error:", response);
                    return response
                },
            },
        }
    });

    // add new service gallery
    // Get a reference to the file input element
    const photoservice = document.querySelector('input[id="galleryservice"]');

    // Create a FilePond instance
    const pondservice = FilePond.create(photoservice, {
        imageResizeTargetHeight: 720,
        imageResizeUpscale: false,
        credits: false,
    })

    pondservice.setOptions({
        server: {
            timeout: 3600000,
            process: {
                url: '<?= base_url("upload/photo") ?>',
                onload: (response) => {
                    console.log("processed:", response);
                    return response
                },
                onerror: (response) => {
                    console.log("error:", response);
                    return response
                },
            },
            revert: {
                url: '<?= base_url("upload/photo") ?>',
                onload: (response) => {
                    console.log("reverted:", response);
                    return response
                },
                onerror: (response) => {
                    console.log("error:", response);
                    return response
                },
            },
        }
    })

    function addNewFacility(val) {
        $('#listFacility').append(`<tr><td><input type="text" class="form-control" name="facility_package[]" required value="${val}"></td></tr>`)
        $('#facility').val('');

    }
</script>
<?= $this->endSection() ?>