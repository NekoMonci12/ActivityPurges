<?php
namespace Pterodactyl\Http\Controllers\Admin\Extensions\activitypurges;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\Support\Facades\DB;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\BlueprintFramework\Libraries\ExtensionLibrary\Admin\BlueprintAdminLibrary as BlueprintExtensionLibrary;

class activitypurgesExtensionController extends Controller
{
    public function __construct(
        private ViewFactory $view,
        private BlueprintExtensionLibrary $blueprint,
    ) {}

    /**
     * Handle both GET and POST requests.
     */
    public function index(Request $request): View
    {
        $message = null;

        if ($request->isMethod('post')) {
            // Validate the input timestamp.
            $validated = $request->validate([
                'timestamp' => 'required|date'
            ]);

            // Convert the HTML datetime-local value to MySQL datetime format.
            $rawTimestamp = $validated['timestamp'];
            $mysqlTimestamp = date('Y-m-d H:i:s', strtotime($rawTimestamp));

            try {
                // Purge records older than the provided timestamp.
                $deleted = DB::table('activity_logs')
                    ->where('timestamp', '<', $mysqlTimestamp)
                    ->delete();

                $message = "{$deleted} log(s) have been purged successfully.";
            } catch (\Exception $e) {
                \Log::error('Purge error: ' . $e->getMessage());
                $message = "An error occurred while purging logs.";
            }
        }

        return $this->view->make('admin.extensions.activitypurges.index', [
            'root' => "/admin/extensions/activitypurges",
            'blueprint' => $this->blueprint,
            'message' => $message,
        ]);
    }

    /**
     * Handle POST requests by delegating to index().
     */
    public function post(Request $request): View
    {
        return $this->index($request);
    }
}
