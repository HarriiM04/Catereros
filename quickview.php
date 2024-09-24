<?php
// Include necessary files and initialize your database connection
include_once 'adminback.php';
$admin = new adminback();

// Check if the 'id' is set in the query string
if (isset($_GET['id'])) {
    $packageId = intval($_GET['id']);

    // Fetch package details by ID
    $package = $admin->display_packageByID($packageId);

    if ($package) {
        ?>
        <div class="wrap-modal1 js-modal1 p-t-60 p-b-20">
            <div class="overlay-modal1 js-hide-modal1"></div>

            <div class="container">
                <div class="bg0 p-t-60 p-b-30 p-lr-15-lg how-pos3-parent">
                    <button class="how-pos3 hov3 trans-04 js-hide-modal1">
                        <img src="images/icons/icon-close.png" alt="CLOSE">
                    </button>

                    <div class="row">
                        <!-- Package Images Section -->
                        <div class="col-md-6 col-lg-7 p-b-30">
                            <div class="p-l-25 p-r-30 p-lr-0-lg">
                                <div class="wrap-slick3 flex-sb flex-w">
                                    <div class="wrap-slick3-dots"></div>
                                    <div class="wrap-slick3-arrows flex-sb-m flex-w"></div>

                                    <div class="slick3 gallery-lb">
                                        <!-- Package Image -->
                                        <div class="item-slick3" data-thumb="images/<?php echo $package['image']; ?>">
                                            <div class="wrap-pic-w pos-relative">
                                                <img src="images/<?php echo $package['image']; ?>" alt="IMG-PACKAGE">

                                                <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="images/<?php echo $package['image']; ?>">
                                                    <i class="fa fa-expand"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <!-- You can add more images dynamically if required -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Package Details Section -->
                        <div class="col-md-6 col-lg-5 p-b-30">
                            <div class="p-r-50 p-t-5 p-lr-0-lg">
                                <h4 class="mtext-105 cl2 js-name-detail p-b-14">
                                    <?php echo $package['name']; ?> <!-- Package Name -->
                                </h4>

                                <span class="mtext-106 cl2">
                                    $<?php echo $package['price']; ?> <!-- Package Price -->
                                </span>

                                <p class="stext-102 cl3 p-t-23">
                                    <?php echo $package['description']; ?> <!-- Package Description -->
                                </p>

                                <!-- Display Package Items by Category -->
                                <div class="p-t-33">
                                    <?php
                                    $items = json_decode($package['items'], true);

                                    if (json_last_error() === JSON_ERROR_NONE && !empty($items)) {
                                        $categories = $admin->getCategoriess(); // Fetch all categories
                                        $categoryMap = [];
                                        while ($category = mysqli_fetch_assoc($categories)) {
                                            $categoryMap[$category['id']] = $category['name'];
                                        }

                                        foreach ($items as $categoryId => $itemIds) {
                                            if (isset($categoryMap[$categoryId])) {
                                                // Fetch item details by their IDs
                                                $ids = implode(',', array_map('intval', $itemIds));
                                                $result = $admin->getItemsByIds($ids);

                                                $itemNames = [];
                                                while ($item = mysqli_fetch_assoc($result)) {
                                                    $itemNames[] = $item['Name'];
                                                }

                                                if (!empty($itemNames)) {
                                                    echo "<b>Category: " . $categoryMap[$categoryId] . "</b>";
                                                    echo "<ul><li>" . implode('</li><li>', $itemNames) . "</li></ul>";
                                                }
                                            }
                                        }
                                    } else {
                                        echo "No items found for this package.";
                                    }
                                    ?>
                                </div>

                                <!-- Add to Cart Button -->
                                <div class="flex-w flex-r-m p-b-10">
                                    <div class="size-204 flex-w flex-m respon6-next">
                                        <button class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail">
                                            Add to cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "Package not found.";
    }
} else {
    echo "No package ID provided.";
}
?>
