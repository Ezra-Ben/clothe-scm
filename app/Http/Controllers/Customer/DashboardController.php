
<?php
/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductRecommendationService;
use App\Models\Product;

class DashboardController extends Controller
{
    /**
     * Display the customer's dashboard with personalized product       	  	recommendations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showCustomerDashboard(Request $request)
    {
        // 1. Get the logged-in customer's ID
        $customerId = auth()->user()->id;

        // 2. Call the ProductRecommendationService to get recommended product IDs
        $productIds = app(ProductRecommendationService::class)
            ->getRecommendedProductIds($customerId);

        // 3. Query the Product model for full details of the recommended products
        $products = Product::whereIn('id', $productIds)->get();

        // 4. Return the dashboard view with the recommended products
        return view('dashboard', compact('products'));
    }
}
*/