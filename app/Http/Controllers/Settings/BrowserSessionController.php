<?php

namespace App\Http\Controllers\Settings;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cjmellor\BrowserSessions\Facades\BrowserSessions;

class BrowserSessionController extends Controller
{
        /**
     * Show the browser sessions settings screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function showBrowserSessions()
    {
        return Inertia::render('settings/browser-session', [
            'sessions' => BrowserSessions::sessions()
        ]);
    }

    public function destroy()
    {
        BrowserSessions::logoutOtherBrowserSessions();

        return back()->with('status', 'Logged out of other browser sessions.');
    }
}
