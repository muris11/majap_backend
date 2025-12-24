<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Album;
use App\Models\Batch;
use App\Models\ContactMessage;
use App\Models\DidYouKnowFact;
use App\Models\HeroSlide;
use App\Models\OrganizationStructure;
use App\Models\Setting;
use App\Models\TimelineEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
    /**
     * Get all public settings
     */
    public function settings(): JsonResponse
    {
        $settings = Cache::remember('settings', 3600, function () {
            return Setting::all()->mapWithKeys(function ($setting) {
                $value = match ($setting->type) {
                    'json' => json_decode($setting->value, true),
                    'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                    'integer' => (int) $setting->value,
                    'image' => $setting->value ? asset(Storage::url($setting->value)) : null,
                    default => $setting->value,
                };

                return [$setting->key => $value];
            });
        });

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * Get hero slides
     */
    public function heroSlides(): JsonResponse
    {
        $slides = Cache::remember('hero_slides', 3600, function () {
            return HeroSlide::active()
                ->ordered()
                ->get()
                ->map(function ($slide) {
                    return [
                        'id' => $slide->id,
                        'image' => asset(Storage::url($slide->image)),
                    ];
                });
        });

        return response()->json([
            'success' => true,
            'data' => $slides,
        ]);
    }

    /**
     * Get did you know facts
     */
    public function didYouKnowFacts(): JsonResponse
    {
        $facts = Cache::remember('did_you_know_facts', 3600, function () {
            return DidYouKnowFact::active()
                ->ordered()
                ->get()
                ->map(function ($fact) {
                    return [
                        'id' => $fact->id,
                        'title' => $fact->title,
                        'image' => asset(Storage::url($fact->image)),
                    ];
                });
        });

        return response()->json([
            'success' => true,
            'data' => $facts,
        ]);
    }

    /**
     * Get all timeline events
     */
    public function timeline(): JsonResponse
    {
        $events = Cache::remember('timeline', 3600, function () {
            return TimelineEvent::ordered()->get();
        });

        return response()->json([
            'success' => true,
            'data' => $events,
        ]);
    }

    /**
     * Get all active batches
     */
    public function batches(): JsonResponse
    {
        $batches = Cache::remember('batches', 3600, function () {
            return Batch::active()
                ->orderBy('year', 'desc')
                ->get();
        });

        return response()->json([
            'success' => true,
            'data' => $batches,
        ]);
    }

    /**
     * Get organization structure
     */
    public function organization(Request $request): JsonResponse
    {
        $batchId = $request->input('batch_id');
        $cacheKey = 'organization_' . ($batchId ?? 'latest');

        $structure = Cache::remember($cacheKey, 3600, function () use ($batchId) {
            if ($batchId) {
                return OrganizationStructure::with('batch')
                    ->ordered()
                    ->where('batch_id', $batchId)
                    ->get()
                    ->map(function ($member) {
                        return $this->formatOrganizationMember($member);
                    });
            } else {
                // Find batch that has organization data, starting from latest
                $batchesWithOrg = Batch::active()
                    ->orderBy('year', 'desc')
                    ->whereHas('organizationStructures')
                    ->first();
                
                if ($batchesWithOrg) {
                    return OrganizationStructure::with('batch')
                        ->ordered()
                        ->where('batch_id', $batchesWithOrg->id)
                        ->get()
                        ->map(function ($member) {
                            return $this->formatOrganizationMember($member);
                        });
                }
                
                return collect([]);
            }
        });

        return response()->json([
            'success' => true,
            'data' => $structure,
        ]);
    }

    private function formatOrganizationMember($member): array
    {
        return [
            'id' => $member->id,
            'position' => $member->position,
            'photo' => $member->photo ? asset(Storage::url($member->photo)) : null,
            'description' => $member->description,
            'level' => $member->level,
            'order' => $member->order,
        ];
    }

    /**
     * Get activities list with pagination and search
     */
    public function activities(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 12);
        $search = $request->input('search');
        $batchId = $request->input('batch_id');

        $query = Activity::with('batch')
            ->published()
            ->orderBy('event_date', 'desc');

        if ($batchId) {
            $query->where('batch_id', $batchId);
        }

        if ($request->has('featured')) {
            $query->featured();
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->has('limit')) {
            $activities = $query->limit($request->limit)->get();
            $data = $activities->map(fn ($activity) => $this->formatActivity($activity));
            
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        }

        $paginated = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $paginated->items() ? collect($paginated->items())->map(fn ($activity) => $this->formatActivity($activity)) : [],
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }

    private function formatActivity(Activity $activity): array
    {
        return [
            'id' => $activity->id,
            'title' => $activity->title,
            'slug' => $activity->slug,
            'short_description' => $activity->short_description,
            'cover_image' => $activity->cover_image ? asset(Storage::url($activity->cover_image)) : null,
            'event_date' => $activity->event_date->format('Y-m-d'),
            'event_date_formatted' => $activity->event_date->translatedFormat('d F Y'),
            'location' => $activity->location,
            'is_featured' => $activity->is_featured,
            'batch' => [
                'id' => $activity->batch->id,
                'name' => $activity->batch->name,
                'year' => $activity->batch->year,
            ],
        ];
    }

    /**
     * Get single activity by slug
     */
    public function activityDetail(string $slug): JsonResponse
    {
        $activity = Activity::with('batch')
            ->published()
            ->where('slug', $slug)
            ->first();

        if (! $activity) {
            return response()->json([
                'success' => false,
                'message' => 'Activity not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $activity->id,
                'title' => $activity->title,
                'slug' => $activity->slug,
                'short_description' => $activity->short_description,
                'content' => $activity->content,
                'cover_image' => $activity->cover_image ? asset(Storage::url($activity->cover_image)) : null,
                'event_date' => $activity->event_date->format('Y-m-d'),
                'event_date_formatted' => $activity->event_date->translatedFormat('d F Y'),
                'location' => $activity->location,
                'is_featured' => $activity->is_featured,
                'batch' => [
                    'id' => $activity->batch->id,
                    'name' => $activity->batch->name,
                    'year' => $activity->batch->year,
                ],
            ],
        ]);
    }

    /**
     * Get related activities
     */
    public function activityRelated(string $slug): JsonResponse
    {
        $activity = Activity::where('slug', $slug)->first();

        if (! $activity) {
            return response()->json([
                'success' => false,
                'message' => 'Activity not found',
            ], 404);
        }

        $related = Activity::published()
            ->where('id', '!=', $activity->id)
            ->where('batch_id', $activity->batch_id)
            ->orderBy('event_date', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'cover_image' => $item->cover_image ? asset(Storage::url($item->cover_image)) : null,
                    'event_date_formatted' => $item->event_date->translatedFormat('d M Y'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $related,
        ]);
    }

    /**
     * Get albums list with pagination and search
     */
    public function albums(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 12);
        $search = $request->input('search');

        $query = Album::with(['batch', 'activity'])
            ->published()
            ->withCount('photos')
            ->orderBy('created_at', 'desc');

        if ($request->has('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $paginated = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $paginated->items() ? collect($paginated->items())->map(fn ($album) => $this->formatAlbum($album)) : [],
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }

    private function formatAlbum(Album $album): array
    {
        return [
            'id' => $album->id,
            'title' => $album->title,
            'slug' => $album->slug,
            'description' => $album->description,
            'cover_image' => $album->cover_image ? asset(Storage::url($album->cover_image)) : null,
            'photos_count' => $album->photos_count,
            'batch' => [
                'id' => $album->batch->id,
                'name' => $album->batch->name,
                'year' => $album->batch->year,
            ],
            'activity' => $album->activity ? [
                'id' => $album->activity->id,
                'title' => $album->activity->title,
                'slug' => $album->activity->slug,
            ] : null,
        ];
    }

    /**
     * Get single album by slug with photos
     */
    public function albumDetail(string $slug): JsonResponse
    {
        $album = Album::with(['batch', 'activity', 'photos' => fn ($q) => $q->ordered()])
            ->published()
            ->where('slug', $slug)
            ->first();

        if (! $album) {
            return response()->json([
                'success' => false,
                'message' => 'Album not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $album->id,
                'title' => $album->title,
                'slug' => $album->slug,
                'description' => $album->description,
                'cover_image' => $album->cover_image ? asset(Storage::url($album->cover_image)) : null,
                'batch' => [
                    'id' => $album->batch->id,
                    'name' => $album->batch->name,
                    'year' => $album->batch->year,
                ],
                'activity' => $album->activity ? [
                    'id' => $album->activity->id,
                    'title' => $album->activity->title,
                    'slug' => $album->activity->slug,
                ] : null,
                'photos' => $album->photos->map(function ($photo) {
                    return [
                        'id' => $photo->id,
                        'image_path' => asset(Storage::url($photo->image_path)),
                        'caption' => $photo->caption,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Get other albums (for sidebar)
     */
    public function albumsOther(string $slug): JsonResponse
    {
        $album = Album::where('slug', $slug)->first();

        if (! $album) {
            return response()->json([
                'success' => false,
                'message' => 'Album not found',
            ], 404);
        }

        $others = Album::published()
            ->where('id', '!=', $album->id)
            ->where('batch_id', $album->batch_id)
            ->withCount('photos')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'cover_image' => $item->cover_image ? asset(Storage::url($item->cover_image)) : null,
                    'photos_count' => $item->photos_count,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $others,
        ]);
    }

    /**
     * Submit contact form
     */
    public function contact(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $message = ContactMessage::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.',
        ], 201);
    }
}
