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
<!-- Begin Page Content -->
<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">List reservation</li>
        </ol>
    </nav>
    <!-- DataTbales  -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary text-center">List reservation Package</h5>
            <a class="btn btn-success" onclick="showReservationModal()" data-bs-toggle="modal" data-bs-target="#reservationModal"> add <i class="fa fa-plus"></i> </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-border" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <?php $no = 1; ?>
                        <tr>
                            <th>No</th>
                            <th>Request Package Name</th>
                            <th>Username</th>
                            <th>Request date</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $reservation) : ?>
                            <?php
                            $reservationId = $reservation['id'];
                            $packageName = $reservation['package_name'];
                            $username = $reservation['username'];
                            $requestDate = $reservation['request_date'];
                            $reservationIdStatus = $reservation['id_reservation_status'];
                            $reservationStatus = $reservation['status'];
                            $dateNow = date("Y-m-d");
                            $depositDate = $reservation['deposit_date'];

                            $proggres = "";
                            if ($reservationIdStatus == 1) {
                                $proggres = "Check Reservation!";
                            } else if ($reservationIdStatus == 2 && $depositDate == null) {
                                $proggres = "Waiting payment document";
                            } else if ($reservationIdStatus == 2 && $depositDate != null) {
                                $proggres = "Check Payment!";
                            } else if ($reservationIdStatus == 3) {
                                $proggres = "Canceled";
                            } else if ($reservationIdStatus == 4) {
                                $proggres = "Transaction Success";
                            }
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $packageName; ?></td>
                                <td><?= $username; ?></td>
                                <td>
                                    <?= $requestDate; ?>
                                </td>
                                <td>
                                    <span class="<?php if ($reservationIdStatus == "1") {
                                                        echo "badge bg-warning";
                                                    } elseif ($reservationIdStatus == "2") {
                                                        echo "badge bg-primary";
                                                    } else if ($reservationIdStatus == "4") {
                                                        echo "badge bg-success";
                                                    } else {
                                                        echo "badge bg-danger";
                                                    } ?>"> <?= $reservationStatus; ?></span>
                                </td>
                                <td>
                                    <?= $proggres; ?>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-outline-success btn-sm " title="confirm" data-bs-toggle="modal" data-bs-target="#reservationModal" onclick="showInfoReservation('<?= $reservationId ?>')">
                                        <i class="fa fa-info"></i>
                                    </a>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--container-fluid -->
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
    new DataTable("#dataTable")

    function showInfoReservation(id) {
        let statusData = JSON.parse('<?= json_encode($statusData) ?>')
        let result
        let reservationStatus, reservationInfo
        $.ajax({
            url: `<?= base_url('reservation/show'); ?>/${id}`,
            type: "GET",
            async: false,
            contentType: "application/json",
            success: function(response) {
                result = JSON.parse(response)

            },
            error: function(err) {
                console.log(err.responseText)
            }
        });

        reservationStatus = result['id_reservation_status']
        if (reservationStatus == '1') {
            reservationInfo =
                `<a class ="btn btn-success" onclick="changeReservationStatus('${id}',2)"> Confirm reservation </a>`

        } else {
            reservationInfo = ''
        }


        $('#modalTitle').html("Reservation Info")
        $('#modalBody').html(`
        <div class="p-2">
               
                <div id="userRating">
    
                </div>
                <div  id="userPayment">
    
                </div>
                <div class="mb-2 shadow-sm p-4 rounded">
                    <p class="text-center fw-bold text-dark"> Reservation Information </p>
                    <table class="table table-borderless text-dark ">
                        <tbody>
                            <tr>
                                <td class="fw-bold">Request By</td>
                                <td>${result['username']}</td>
                            </tr>
                            
                            <tr>
                                <td class="fw-bold">Request Package </td>
                                <td>${result['package_name']}</td>
                            </tr>
                                                
                            <tr>
                                <td class="fw-bold">Request Date</td>
                                <td>${result['request_date']}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Total people</td>
                                <td>${result['number_people']}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Costum package</td>
                                <td class="${result['package_costum'] == '1' ? 'badge bg-success' : ''}">${result['package_costum'] == '2' ? 'no' : 'yes'}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Additional Information</td>
                                <td>${result['comment']!= null ? result['comment'] : '-'}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Price </td>
                                <td >${rupiah(result['package_price'])}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold"> </td>
                                <td>${reservationInfo}</td>
                            </tr>           
                        </tbody>
                    </table>
                </div>    
                <div class="shadow-sm p-4 rounded">
                     <p class="text-center fw-bold text-dark"> Reservation Status </p>
                     <fieldset class="form-group mb-4">
                        <label for="statusReservation" class="mb-2"> Status reservation </label>
                        <select class="form-select" id="statusReservation" required>
                                
                      </fieldset>
                </div>
            </div>
        `)

        // user status
        $("#statusReservation").html(`<option value="${result['id_reservation_status']}"> ${result['status']} ( current status )</option>`)
        for (i in statusData) {
            if (statusData[i].id != result['id_reservation_status']) {
                $("#statusReservation").append(`
                <option  value="${statusData[i].id}">  ${statusData[i].status} </option>
                `)
            }
        }
        $("#statusReservation").on("change", function() {
            let statusReservation = $("#statusReservation").val()
            changeReservationStatus(id, statusReservation)

        })

        // user payment
        if (result['proof_of_deposit'] != null) {
            let depositDate = result['deposit_date']
            let deposit = result['deposit']
            let proofDeposit = result['proof_of_deposit']

            $("#userPayment").addClass("mb-2 shadow-sm p-4 rounded")
            $("#userPayment").html(`
                <p class="text-center fw-bold text-dark"> Payment Information </p>
                <p> Deposit on : ${depositDate} </p>
                <p> Deposit : ${rupiah(deposit)} </p>
                <div class="mb-2">
                    <img class="img-fluid img-thumbnail rounded" src="${'<?= base_url() ?>' + '/media/photos/reservation/' + proofDeposit }" width="100%">
                </div>
               
            `)

            if (result['id_reservation_status'] == '4') {
                $("#userPayment").append(`
                <div class="text-end">
                <span class="badge bg-success"> payed </span>
                </div>
                `)
            } else {
                $("#userPayment").append(`
                <div class="text-end">
                <a class="btn btn-success" onclick="changeReservationStatus('${id}',4,'payment')"> Accept payment</a>
                </div>
                `)
            }
        }


        // user rating
        if (result['rating'] != null) {
            let rating = result['rating']
            let updatedRating = result['updated_at']
            let review = result['review'] != null ? result['review'] : ''
            console.log(result['rating'])
            $("#userRating").addClass("mb-2 shadow-sm p-4 rounded")
            $("#userRating").html(`
                <p class="text-center fw-bold text-dark"> Rated And Reviewed </p>
                <p> Rated on : ${updatedRating} </p>
                <div class="star-containter mb-3 text-start">
                <i class="fa-solid fa-star fs-10" id="star-1" ></i>
                <i class="fa-solid fa-star fs-10" id="star-2" ></i>
                <i class="fa-solid fa-star fs-10" id="star-3" ></i>
                <i class="fa-solid fa-star fs-10" id="star-4" ></i>
                <i class="fa-solid fa-star fs-10" id="star-5" ></i>
                </div>
                <p> ${review} </p>
            `)
            setStar(rating)
        }


        $('#modalFooter').html(``)
    }
    const rupiah = (number) => {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR"
        }).format(number);
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

    function changeReservationStatus(id, status, paymentDate = null) {

        let requestData = {
            id_reservation_status: status, //status
            payment_date: paymentDate
        }
        console.log(requestData)
        $.ajax({
            url: `<?= base_url('reservation/update'); ?>/${id}`,
            type: "PUT",
            data: requestData,
            async: false,
            contentType: "application/json",
            success: function(response) {
                Swal.fire(
                    'Reservation updated',
                    '',
                    'success'
                ).then(() => {
                    window.location.reload()
                });
            },
            error: function(err) {
                console.log(err.responseText)
            }
        });
    }
</script>
<script>
    function showReservationModal() {
        <?php if (in_groups('admin')) : ?>
            $('#modalTitle').html("Reservation")
            $('#modalBody').html(`
            <label for="id_user" class="mb-2">User</label>
                    <select class="form-select" id="id_user" required>
                                    <?php if ($userData) : ?>
                                        <?php $no = 0; ?>       
                                        <?php foreach ($userData as $user) : ?>
                                           
                                    <option value="<?= esc($user->id); ?>" <?= ($no == 0) ? 'selected' : ''; ?>>  <?= esc($user->username); ?></option>
                                        
                                            <?php $no++; ?>       
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">User not found</option>
                                    <?php endif; ?>
                     </select>
            <label for="id_package" class="mb-2"> Package name </label>
                    <select class="form-select" id="id_package" required>
                        <?php if ($packageData) : ?>
                           <?php $no = 0; ?>       
                           <?php foreach ($packageData as $package) : ?>
                                           
                             <option value="<?= esc($package->id); ?>" <?= ($no == 0) ? 'selected' : ''; ?>>  <?= esc($package->name); ?></option>
                                
                           <?php $no++; ?>       
                           <?php endforeach; ?>
                        <?php else : ?>
                            <option value="">Package not found</option>
                        <?php endif; ?>
                     </select>
                        <?php if ($packageData) : ?>
                            <?php $no = 0; ?>       
                            <?php foreach ($packageData as $package) : ?>
                                <input type="hidden" value="<?= esc($package->capacity); ?>" id="capacity_of_package<?= esc($package->id); ?>" required >   
                             <?php $no++; ?>       
                            <?php endforeach; ?>
                        <?php endif ?>
            <div class="form-group mb-2">
                <label for="reservation_date" class="mb-2">Reservation date </label>
                <input type="date" id="reservation_date" class="form-control" required >
            </div>
            <div class="form-group mb-2">
                <label for="number_people" class="mb-2"> Number of people </label>
                <input type="number" id="number_people" class="form-control" required >
            </div>
            <div class="form-group mb-2">
                <label for="comment" class="mb-2"> Comment </label>
                <input type="text" id="comment" class="form-control"  >
            </div>
            <div class="form-group mb-2">
                <label for="status" class="mb-2"> Status reservation </label>
                <select class="form-select" id="status" required>
                            <?php if ($statusData) : ?>
                            <?php $no = 0; ?>       
                            <?php foreach ($statusData as $status) : ?>
                                            
                                <option value="<?= esc($status->id); ?>" <?= ($no == 1) ? 'selected' : ''; ?>>  <?= esc($status->status); ?></option>
                                    
                            <?php $no++; ?>       
                            <?php endforeach; ?>
                            <?php else : ?>
                                <option value="">Package not found</option>
                            <?php endif; ?>
            </div>
            `)
            $('#modalFooter').html(`<a class="btn btn-success" onclick="makeReservation()"> Make reservation </a>`)
        <?php endif; ?>
    }

    function makeReservation() {
        let userId = $("#id_user").val()
        let packageId = $("#id_package").val()
        let status = $("#status").val()
        let capacityOfPackage = $(`#capacity_of_package${packageId}`).val()
        let reservationDate = $("#reservation_date").val()
        let numberPeople = $("#number_people").val()
        let comment = $("#comment").val()
        let numberCheckResult = checkNumberPeople(numberPeople, capacityOfPackage)
        let dateCheckResult = checkIsDateExpired(reservationDate)
        let sameDateCheckResult = "true"
        if (reservationDate) {
            sameDateCheckResult = checkIsDateDuplicate(userId, reservationDate)
        }

        if (!reservationDate) {
            Swal.fire('Please select reservation date', '', 'warning');
        } else if (numberPeople <= 0) {
            Swal.fire('Need 1 people at least', '', 'warning');
        } else if (numberCheckResult == false) {
            Swal.fire('Out of capacity, maksimal ' + `${capacityOfPackage}` + ' people', '', 'warning');
        } else if (dateCheckResult == false) {
            Swal.fire('Cannot Reserve, out of date, maksimal H-1 reservation', '', 'warning');
        } else if (sameDateCheckResult == "true") {
            Swal.fire('Already chose the same date! please select another date', '', 'warning');
        } else {
            <?php if (in_groups('admin')) : ?>
                let requestData = {
                    reservation_date: reservationDate,
                    id_user: userId,
                    id_package: packageId,
                    id_reservation_status: status, // pending status
                    number_people: numberPeople,
                    comment: comment
                }
                $.ajax({
                    url: `<?= base_url('web/reservation/create'); ?>`,
                    type: "POST",
                    data: requestData,
                    async: false,
                    contentType: "application/json",
                    success: function(response) {
                        Swal.fire(
                            'Success to make reservation request',
                            '',
                            'success'
                        ).then(() => {
                            window.location.replace('<?= base_url() ?>' + '/dashboard/reservation/')
                        });

                    },
                    error: function(err) {
                        console.log(err.responseText)
                    }
                });
            <?php endif; ?>
        }
    }

    function checkNumberPeople(numberPeople, capacityOfPackage) {
        let packageCapacity = parseInt(capacityOfPackage)
        let peopleNumberRequest = parseInt(numberPeople)

        if (peopleNumberRequest > packageCapacity) {
            return false
        } else {
            return true
        }
    }

    function checkIsDateExpired(reservation_date) {
        let result

        let today = new Date();
        let dd = String(today.getDate()).padStart(2, '0');
        let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        let yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;

        if (reservation_date > today) {
            result = true
        } else {
            result = false
        }
        return result
    }

    function checkIsDateDuplicate(user_id, reservation_date) {
        let result
        $.ajax({
            url: `<?= base_url('web') ?>/check/${user_id}/${reservation_date}`,
            type: "GET",
            async: false,
            success: function(response) {
                result = response
            },
            error: function(err) {
                console.log(err.responseText)
            }
        })
        return result
    }
</script>
<?= $this->endSection() ?>