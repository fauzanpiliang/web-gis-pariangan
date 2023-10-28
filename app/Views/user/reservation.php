<?= $this->extend('layout/template.php') ?>
<?= $this->section('content') ?>
<!-- Modal  -->
<div class="modal fade text-left" id="reservationModal" tabindex="-1" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
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
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">My Reservation</h3>
            <span id="multipleButton">
                <a title="Print multiple reservation" class="btn btn-primary" onclick="openMultipleCheckOut()"> <i class="fa-solid fa-print"></i> Print in Group </a>
            </span>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-border" id="dataTable">
                    <thead>
                        <tr>
                            <th class="text-start"> #</th>
                            <th class="text-start"> Tourism package name / ID </th>
                            <th class="text-start"> Reservation date </th>
                            <th class="text-start"> Number of people </th>
                            <th class="text-start"> Status </th>
                            <th class="text-center  checkSingle"> Action </th>
                            <th class="text-center  d-none checkAll"> Action</th>
                            <th class="text-center"> Rate / review </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php if ($data != null) : ?>

                            <?php foreach ($data as $item) : ?>
                                <?php
                                $packageId = $item['id'];
                                $packageName = $item['package_name'];
                                $requestDate = $item['request_date'];
                                $numberPeople = $item['number_people'];
                                $reservationIdStatus = $item['id_reservation_status'];
                                $statusReservation = $item['status'];
                                $rating = $item['rating'];
                                $comment = $item['comment'];
                                $dateNow = date("Y-m-d");
                                ?>
                                <tr>
                                    <td class="text-start"> <?= $no; ?> </td>
                                    <td class="text-start"> <?= $packageName; ?>/<?= $packageId ?></td>
                                    <td class="text-start"> <?= $requestDate; ?> </td>
                                    <td class="text-start"> <?= $numberPeople; ?> </td>
                                    <td class="text-start">
                                        <span class="badge bg-<?php if ($reservationIdStatus == 1) {
                                                                    echo "warning";
                                                                } else if ($reservationIdStatus == 2) {
                                                                    echo "success";
                                                                } else if ($reservationIdStatus == 3) {
                                                                    echo "danger";
                                                                }; ?>">
                                            <?= $statusReservation ?>
                                        </span>
                                    </td>

                                    <td class="text-center checkSingle">
                                        <?php if ($reservationIdStatus == 2 && $requestDate > $dateNow) : ?>
                                        <?php endif; ?>
                                        <a href=""><i class="fa fa-upload text-secondary"></i></a>
                                        <a class="btn <?= $reservationIdStatus == 2 && $requestDate > $dateNow ? '' : 'disabled' ?>" onclick="openInvoice('<?= $packageId ?>')"><i class="fa fa-print text-primary" title="print invoice"></i> </a>


                                        <a title="cancel reservation" class="btn" data-bs-toggle="modal" data-bs-target="#reservationModal" onclick="showModalDelete('<?= $packageId ?>')"> <i class="fa fa-x text-danger"></i></a>


                                    </td>
                                    <td class="d-none text-center checkAll">
                                        <input type="checkbox" <?= $reservationIdStatus == 2 && $requestDate > $dateNow ? '' : 'disabled' ?> name="idPackage[]" value="<?= $packageId ?>">
                                    </td>
                                    <td class="text-center" id="action<?= $packageId ?>">
                                        <?php if ($reservationIdStatus == 2 && $rating == null && $requestDate < $dateNow) : ?>
                                            <a title="rate package" class="btn " data-bs-toggle="modal" data-bs-target="#reservationModal" onclick="openModalRatingReservation('<?= $packageId ?>')"> <i class="fa fa-star text-warning"></i></a>
                                        <?php elseif ($reservationIdStatus == 2 && $rating != null && $requestDate < $dateNow) : ?>
                                            <a title="rate package" class="btn " data-bs-toggle="modal" data-bs-target="#reservationModal" onclick="openInfoRating('<?= $rating ?>','<?= $comment ?>','<?= $item['updated_at']; ?>')"> <i class="fa fa-check text-primary"></i></a>
                                        <?php else : ?>

                                        <?php endif; ?>


                                    </td>
                                </tr>
                                <?php $no++; ?>
                            <?php endforeach; ?>

                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
    new DataTable('#dataTable')

    function openInfoRating(rating, comment, updated_at) {

        $('#modalTitle').html("Thanks for rating and reviewed")
        $('#modalBody').html(`
            <p>  ${updated_at} </p>
            <div class="star-containter mb-3 text-start">
               <i class="fa-solid fa-star fs-10" id="star-1" ></i>
               <i class="fa-solid fa-star fs-10" id="star-2" ></i>
               <i class="fa-solid fa-star fs-10" id="star-3" ></i>
               <i class="fa-solid fa-star fs-10" id="star-4" ></i>
               <i class="fa-solid fa-star fs-10" id="star-5" ></i>
             </div>
             <p>  ${comment} </p>
        `)
        setStar(rating)
        $("#modalFooter").html()
    }

    function openModalRatingReservation(reservationId) {
        let url = `<?= base_url('review/package') ?>`
        $('#modalTitle').html("Please rate and review")
        $("#modalBody").html(`
                <?php if (in_groups('user')) : ?>
                    <form class="form form-vertical" action="${url}" method="post" onsubmit="checkStar(event);">
                        <div class="form-body">
                            <div class="star-containter mb-3 text-center">
                                <i class="fa-solid fa-star fs-4" id="star-1" onclick="setStar('1');"></i>
                                <i class="fa-solid fa-star fs-4" id="star-2" onclick="setStar('2');"></i>
                                <i class="fa-solid fa-star fs-4" id="star-3" onclick="setStar('3');"></i>
                                <i class="fa-solid fa-star fs-4" id="star-4" onclick="setStar('4');"></i>
                                <i class="fa-solid fa-star fs-4" id="star-5" onclick="setStar('5');"></i>
                                <input type="hidden" id="star-rating" value="0" name="rating">
                                <input type="hidden" value="<?= user()->id ?>" name="id_user">
                                <input type="hidden" value="${reservationId}" name="id_reservation">
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 150px;" name="comment"></textarea>
                                    <label for="floatingTextarea">Leave a comment here</label>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-end mb-3">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
        `)
        $('#modalFooter').html()

    }
    // Validate if star rating picked yet
    function checkStar(event) {
        const star = document.getElementById('star-rating').value;
        if (star == '0') {
            event.preventDefault();
            Swal.fire('Please put rating star');
        }
    }

    function setStar(star) {
        $("#star-rating").val(star)
        switch (star) {
            case '1':
                $("#star-1").addClass('star-checked')
                $("#star-2,#star-3,#star-4,#star-5").removeClass('star-checked')
                break
            case '2':
                $("#star-1,#star-2").addClass('star-checked')
                $("#star-3,#star-4,#star-5").removeClass('star-checked')
                break
            case '3':
                $("#star-1,#star-2,#star-3").addClass('star-checked')
                $("#star-4,#star-5").removeClass('star-checked')
                break
            case '4':
                $("#star-1,#star-2,#star-3,#star-4").addClass('star-checked')
                $("#star-5").removeClass('star-checked')
                break
            case '5':
                $("#star-1,#star-2,#star-3,#star-4,#star-5").addClass('star-checked')
                break
        }
    }





    function showModalDelete(id) {
        $('#modalTitle').html("Abort reservation")
        $('#modalBody').html(`Are you sure delete this reservation?`)
        $('#modalFooter').html(`<a class="btn btn-danger" onclick="deleteReservation('${id}')"> Delete </a>`)
    }

    function deleteReservation(id_reservation) {
        $.ajax({
            url: `<?= base_url('reservation/delete') ?>/${id_reservation}`,
            type: "DELETE",
            async: false,
            contentType: "application/json",
            success: function(response) {

                Swal.fire(
                    'Reservation deleted',
                    '',
                    'success'
                ).then(() => {
                    window.location.replace("<?= base_url('user/reservation') ?>" + "/" + "<?= user()->id ?>")
                });
            },
            error: function(err) {
                console.log(err.responseText)
            }
        });
    }

    function openMultipleCheckOut() {
        $("#multipleButton").html(`
        <a title="closeAll" class="btn btn-danger" onclick="closeMultipleCheckOut()"><i class="fa fa-x"></i> Cancel Group </a>
        <a title="Print All" class="btn btn-primary" onclick="openInvoice()"><i class="fa fa-print"></i> Print selected</a>
        `)
        $(".checkAll").removeClass("d-none")
        $(".checkSingle").addClass("d-none")
    }

    function closeMultipleCheckOut() {
        $("#multipleButton").html(`
        <a title="Print multiple reservation" class="btn btn-primary" onclick="openMultipleCheckOut()"><i class="fa-solid fa-print"></i> Print in Group </a>
        `)
        $(".checkAll").addClass("d-none")
        $(".checkSingle").removeClass("d-none")
    }

    function openInvoice(single = null) {
        let invoiceRequest

        single != null ?
            invoiceRequest = [single] :
            invoiceRequest = $('input[name="idPackage[]"]:checked').map(function() {
                return this.value; // $(this).val()
            }).get();


        if (invoiceRequest.length > 0) {
            $.ajax({
                url: '<?= base_url("pdf/invoice-data") ?>',
                type: "POST",
                dataType: "json",
                data: {
                    id_reservation: invoiceRequest
                },
                success: function(response) {

                    window.open('<?= base_url('pdf/index'); ?>' + '/' + JSON.stringify(response));
                },
                error: function(err) {
                    console.log(err.responseText)
                }
            })
        } else {
            Swal.fire(
                'Please select 1 reservation at least!',
                '',
                'error'
            )
        }

    }
</script>

<?= $this->endSection() ?>