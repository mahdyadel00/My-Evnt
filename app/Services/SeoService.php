<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Event;
use App\Models\Setting;

/**
 * SEO Service for generating meta tags
 *
 * This service provides methods to generate SEO meta tags
 * for different pages including events and general pages.
 */
class SeoService
{
    /**
     * Generate SEO meta tags for an event
     *
     * @param Event $event
     * @return array
     */
    public function generateEventMetaTags(Event $event): array
    {
        $eventImage = $event->media->where('name', 'banner')->first();
        $eventUrl = route('event', $event->uuid);

        return [
            'title' => $event->meta_title ?? $event->name,
            'description' => $event->meta_description ?? $this->stripHtmlAndLimit($event->description ?? '', 160),
            'keywords' => $event->meta_keywords ?? $this->generateEventKeywords($event),
            'image' => $eventImage ? asset('storage/' . $eventImage->path) : null,
            'url' => $eventUrl,
            'type' => 'event',
            'site_name' => $this->getSiteName(),
        ];
    }

    /**
     * Generate SEO meta tags for general pages
     *
     * @param string|null $title
     * @param string|null $description
     * @param string|null $keywords
     * @param string|null $image
     * @param string|null $url
     * @return array
     */
    public function generateGeneralMetaTags(
        ?string $title = null,
        ?string $description = null,
        ?string $keywords = null,
        ?string $image = null,
        ?string $url = null
    ): array {
        $setting = Setting::first();

        return [
            'title'                     => $title ?? $setting?->meta_title ?? $setting?->site_name ?? $setting?->name ?? config('app.name'),
            'description'               => $description ?? $setting?->meta_description ?? $setting?->description ?? '',
            'keywords'                  => $keywords ?? $setting?->meta_keywords ?? '',
            'image'                     => $image ?? $this->getDefaultImage(),
            'url'                       => $url ?? url()->current(),
            'type'                      => 'website',
            'site_name'                 => $this->getSiteName(),
        ];
    }

    /**
     * Generate keywords for an event based on its data
     *
     * @param Event $event
     * @return string
     */
    private function generateEventKeywords(Event $event): string
    {
        $keywords = [];

        if ($event->name) {
            $keywords[] = $event->name;
        }

        if ($event->category) {
            $keywords[] = $event->category->name;
        }

        if ($event->city) {
            $keywords[] = $event->city->name;
        }

        if ($event->organized_by || $event->company?->company_name) {
            $keywords[] = $event->company?->company_name ?? $event->organized_by;
        }

        if ($event->location) {
            $keywords[] = $event->location;
        }

        $keywords[] = 'events';
        $keywords[] = 'tickets';
        $keywords[] = $event->format ? 'online event' : 'offline event';

        return implode(', ', array_unique($keywords));
    }

    /**
     * Get site name from settings
     *
     * @return string
     */
    private function getSiteName(): string
    {
        $setting = Setting::first();
        return $setting?->site_name ?? $setting?->name ?? config('app.name', 'MyEvnt');
    }

    /**
     * Get default image for SEO
     *
     * @return string|null
     */
    private function getDefaultImage(): ?string
    {
        $setting = Setting::first();
        $logo = $setting?->media->where('name', 'header_logo')->first();

        return $logo ? asset('storage/' . $logo->path) : null;
    }

    /**
     * Strip HTML tags and limit text length
     *
     * @param string $text
     * @param int $limit
     * @return string
     */
    private function stripHtmlAndLimit(string $text, int $limit = 160): string
    {
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        if (mb_strlen($text) > $limit) {
            $text = mb_substr($text, 0, $limit);
            $text = mb_substr($text, 0, mb_strrpos($text, ' ')) . '...';
        }

        return $text;
    }
}

