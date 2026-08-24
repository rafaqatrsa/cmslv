<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\FrontendRegistrationRequest;
use App\Models\Branch;
use App\Models\Enquiry;
use App\Models\Franchise;
use App\Models\FrontCmsSetting;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    public function index(): View
    {
        $settings = $this->settings();
        $frontSettings = $this->frontSettings();
        $homePage = Page::query()->active()->published()->where('is_homepage', 1)->latest('created_at')->first();
        $branches = Branch::query()->active()->latest('created_at')->limit(8)->get();
        $posts = Post::query()->active()->published()->latest('publish_date')->limit(6)->get();
        $gallery = Gallery::query()->latest('created_at')->limit(8)->get();
        $seo = $this->seo($homePage, 'Home');

        return view('frontend.index', compact('settings', 'frontSettings', 'homePage', 'branches', 'posts', 'gallery', 'seo'));
    }

    public function page(string $branch, string $slug): View
    {
        $branchRecord = $this->findBranch($branch);
        $page = Page::query()
            ->active()
            ->published()
            ->forBranch($branchRecord)
            ->where(fn (Builder $query) => $query->bySlug($slug))
            ->firstOrFail();
        $settings = $this->settings($branchRecord);
        $frontSettings = $this->frontSettings($branchRecord);
        $seo = $this->seo($page, $page->title ?? 'Page');

        return view('frontend.page', compact('branchRecord', 'page', 'settings', 'frontSettings', 'seo'));
    }

    public function read(string $branch, string $slug): View
    {
        $branchRecord = $this->findBranch($branch);
        $post = Post::query()
            ->active()
            ->published()
            ->forBranch($branchRecord)
            ->where(fn (Builder $query) => $query->bySlug($slug))
            ->firstOrFail();
        $relatedPosts = Post::query()
            ->active()
            ->published()
            ->forBranch($branchRecord)
            ->whereKeyNot($post->id)
            ->latest('publish_date')
            ->limit(4)
            ->get();
        $settings = $this->settings($branchRecord);
        $frontSettings = $this->frontSettings($branchRecord);
        $seo = $this->seo($post, $post->title ?? 'Read');

        return view('frontend.read', compact('branchRecord', 'post', 'relatedPosts', 'settings', 'frontSettings', 'seo'));
    }

    public function branch(int $id): View
    {
        $branch = Branch::query()
            ->with([
                'pages' => fn ($query) => $query->active()->published()->latest('publish_date'),
                'posts' => fn ($query) => $query->active()->published()->latest('publish_date'),
                'galleries' => fn ($query) => $query->latest('created_at')->limit(12),
                'setting',
                'frontCmsSetting',
            ])
            ->findOrFail($id);
        $settings = $branch->setting ?? $this->settings();
        $frontSettings = $branch->frontCmsSetting ?? $this->frontSettings();
        $seo = $this->seo(null, $branch->name ?? 'Branch', $settings?->address);

        return view('frontend.branch', compact('branch', 'settings', 'frontSettings', 'seo'));
    }

    public function franchises(): View
    {
        $settings = $this->settings();
        $frontSettings = $this->frontSettings();
        $franchises = Franchise::query()->active()->franchiseLocations()->latest('created_at')->paginate(12);
        $seo = $this->seo(null, 'Franchises');

        return view('frontend.franchises', compact('settings', 'frontSettings', 'franchises', 'seo'));
    }

    public function franchiseOffer(): View
    {
        $settings = $this->settings();
        $frontSettings = $this->frontSettings();
        $page = $this->optionalPage('franchiseoffer');
        $seo = $this->seo($page, 'Franchise Offer');

        return view('frontend.franchise-offer', compact('settings', 'frontSettings', 'page', 'seo'));
    }

    public function register(): View
    {
        $settings = $this->settings();
        $frontSettings = $this->frontSettings();
        $branches = Branch::query()->active()->orderBy('name')->get();
        $seo = $this->seo(null, 'Register');

        return view('frontend.register', compact('settings', 'frontSettings', 'branches', 'seo'));
    }

    public function storeRegistration(FrontendRegistrationRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            Enquiry::query()->create($this->enquiryPayload($request->validated(), 'online_registration'));
        });

        return redirect()->route('frontend.register')->with('status', 'Your registration request has been submitted.');
    }

    public function privacyPolicy(): View
    {
        $settings = $this->settings();
        $frontSettings = $this->frontSettings();
        $page = $this->optionalPage('privacypolicy');
        $seo = $this->seo($page, 'Privacy Policy');

        return view('frontend.privacy-policy', compact('settings', 'frontSettings', 'page', 'seo'));
    }

    public function contactUs(): View
    {
        $settings = $this->settings();
        $frontSettings = $this->frontSettings();
        $branches = Branch::query()->active()->orderBy('name')->get();
        $seo = $this->seo(null, 'Contact Us', $settings?->address);

        return view('frontend.contact-us', compact('settings', 'frontSettings', 'branches', 'seo'));
    }

    public function storeContact(ContactRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            Enquiry::query()->create($this->enquiryPayload($request->validated(), 'website_contact'));
        });

        return redirect()->route('frontend.contact-us')->with('status', 'Your message has been received.');
    }

    private function findBranch(string $identifier): Branch
    {
        return Branch::query()->active()->matchingIdentifier($identifier)->firstOrFail();
    }

    private function optionalPage(string $slug): ?Page
    {
        return Page::query()
            ->active()
            ->published()
            ->where(fn (Builder $query) => $query->bySlug($slug))
            ->latest('created_at')
            ->first();
    }

    private function settings(?Branch $branch = null): ?Setting
    {
        return Setting::query()
            ->when($branch, fn (Builder $query) => $query->where('brc_id', $branch->id))
            ->latest('created_at')
            ->first();
    }

    private function frontSettings(?Branch $branch = null): ?FrontCmsSetting
    {
        return FrontCmsSetting::query()
            ->when($branch, fn (Builder $query) => $query->where('brc_id', $branch->id))
            ->latest('created_at')
            ->first();
    }

    /**
     * @return array{title: string, description: string, keywords: string, canonical_url: string, og_title: string, og_description: string, og_image: ?string}
     */
    private function seo(Page|Post|null $content, string $fallbackTitle, ?string $fallbackDescription = null): array
    {
        $title = $content?->meta_title ?: $content?->title ?: $fallbackTitle;
        $description = $content?->meta_description ?: $fallbackDescription ?: '';

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $content?->meta_keyword ?: '',
            'canonical_url' => url()->current(),
            'og_title' => $title,
            'og_description' => $description,
            'og_image' => $content?->feature_image ?: $content?->image,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function enquiryPayload(array $data, string $source): array
    {
        return [
            'brc_id' => $data['brc_id'] ?? null,
            'name' => $data['name'],
            'contact' => $data['contact'],
            'email' => $data['email'] ?? null,
            'father_name' => $data['father_name'] ?? null,
            'address' => $data['address'] ?? 'Website contact form',
            'phone' => $data['contact'],
            'reference' => 'website',
            'date' => now()->toDateString(),
            'description' => $data['description'] ?? 'Online registration request',
            'follow_up_date' => now()->toDateString(),
            'note' => $data['description'] ?? '',
            'source' => $source,
            'status' => 'new',
            'created_by' => 0,
        ];
    }
}
