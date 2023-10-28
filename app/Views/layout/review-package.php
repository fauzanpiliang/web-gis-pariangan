<!-- Object Rating and Review -->
<div class="card">
    <div class="card-header text-center">
        <h4 class="card-title">Rating and Review</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="reviews">
                <tbody id="commentBody">
                    <?php if (isset($data['reviews'])) : ?>
                        <?php foreach ($data['reviews'] as $review) : ?>
                            <?php if ($review['rating'] != null || $review['review'] != null) : ?>
                                <tr>
                                    <td>
                                        <?php if (isset($review['rating'])) : ?>
                                            <div class="star-containter mb-3">
                                                <i class="fa-solid fa-star fs-10" id="star-1"></i>
                                                <i class="fa-solid fa-star fs-10" id="star-2"></i>
                                                <i class="fa-solid fa-star fs-10" id="star-3"></i>
                                                <i class="fa-solid fa-star fs-10" id="star-4"></i>
                                                <i class="fa-solid fa-star fs-10" id="star-5"></i>
                                            </div>
                                            <script>
                                                setStar("<?= $review['rating']; ?>")
                                            </script>
                                        <?php endif; ?>
                                        <p class="mb-0"><?= $review['name']; ?> </p>
                                        <p class="fw-light"><?= $review['date']; ?></p>
                                        <p class="fw-bold"><?= $review['review']; ?></p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>