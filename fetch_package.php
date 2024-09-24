<?php
include_once 'adminback.php';
$admin = new adminback();

if (isset($_GET['id'])) {
    $packageId = intval($_GET['id']);
    
    // Fetch package details
    $package = $admin->display_packageByID($packageId);

    // Fetch item names and category names based on item IDs in the package
    $items = json_decode($package['items'], true); // Decode items to get IDs

    $itemDetails = [];
    if (json_last_error() === JSON_ERROR_NONE && !empty($items)) {
        // Fetch all categories to create a mapping
        $categoriesResult = $admin->getCategoriess();
        $categoryMap = [];
        while ($category = mysqli_fetch_assoc($categoriesResult)) {
            $categoryMap[$category['id']] = $category['name'];
        }

        // Fetch item details by their IDs
        foreach ($items as $categoryId => $itemIds) {
            $ids = implode(',', array_map('intval', $itemIds)); // Sanitize IDs
            $result = $admin->getItemsByIds($ids); // Method to fetch items
            
            while ($item = mysqli_fetch_assoc($result)) {
                $itemDetails[$categoryMap[$categoryId]][] = $item['Name']; // Store item names by category name
            }
        }
    }

    // Add item details to the package data
    $package['itemDetails'] = $itemDetails;

    // Return the package data as JSON
    echo json_encode($package);
}
?>
