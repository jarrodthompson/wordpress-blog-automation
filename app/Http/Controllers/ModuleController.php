<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Automation;
use App\Models\Comment;
use App\Models\Feature;
use App\Models\MediaItem;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Services\BrandContext;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function __construct(private BrandContext $ctx) {}

    private function brand()
    {
        $brand = $this->ctx->current();
        abort_if(! $brand, 404, 'No brand configured. Run the seeder.');
        return $brand;
    }

    public function blogManager(Request $request)
    {
        $brand = $this->brand();
        $query = Post::where('brand_id', $brand->id);
        if ($request->filled('stage')) {
            $query->where('status', $request->get('stage'));
        }
        $posts = $query->orderByDesc('updated_at')->get();
        $counts = Post::where('brand_id', $brand->id)
            ->selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');

        return view('pages.blog-manager', compact('brand', 'posts', 'counts'))
            ->with('activeStage', $request->get('stage'));
    }

    public function aiImage()
    {
        $brand = $this->brand();
        $media = MediaItem::where('brand_id', $brand->id)->latest('id')->limit(24)->get();
        $total = MediaItem::where('brand_id', $brand->id)->count();

        return view('pages.ai-image', compact('brand', 'media', 'total'));
    }

    public function crm()
    {
        $brand = $this->brand();
        $automations = Automation::where('brand_id', $brand->id)->get();

        return view('pages.crm', compact('brand', 'automations'));
    }

    public function scoreboard()
    {
        $brand = $this->brand();
        $orders = Order::where('brand_id', $brand->id)->orderByDesc('placed_at')->get();
        $revenue = $orders->where('status', '!=', 'refunded')->sum('total');

        return view('pages.scoreboard', compact('brand', 'orders', 'revenue'));
    }

    public function products()
    {
        $brand = $this->brand();
        $products = Product::where('brand_id', $brand->id)->orderBy('name')->get();

        return view('pages.products', compact('brand', 'products'));
    }

    public function autoblog()
    {
        $brand = $this->brand();
        $scheduled = Post::where('brand_id', $brand->id)->whereNotNull('scheduled_at')
            ->orderBy('scheduled_at')->get();
        $planned = Post::where('brand_id', $brand->id)->where('status', 'planned')
            ->whereNull('scheduled_at')->get();

        return view('pages.autoblog', compact('brand', 'scheduled', 'planned'));
    }

    public function activity()
    {
        $brand = $this->brand();
        $activities = Activity::where('brand_id', $brand->id)->orderByDesc('occurred_at')->paginate(20);

        return view('pages.activity', compact('brand', 'activities'));
    }

    public function features()
    {
        $features = Feature::orderByDesc('votes')->get()->groupBy('status');
        $brand = $this->brand();

        return view('pages.features', compact('brand', 'features'));
    }

    public function brandDna()
    {
        $brand = $this->brand();

        return view('pages.brand-dna', compact('brand'));
    }

    public function settings()
    {
        $brand = $this->brand();

        return view('pages.settings', compact('brand'));
    }
}
