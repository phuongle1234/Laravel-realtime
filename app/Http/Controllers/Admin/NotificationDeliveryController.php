<?php

namespace App\Http\Controllers\Admin;

use  App\Repositories\NotificationDeliveryRepository;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Requests\NotificationDeliveryRequest;
use Illuminate\Http\Request;
use App\Enums\EUser;
use App\Models\User;


class NotificationDeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $_repo;
	private $_order_by;

	const URL_INDEX = "admin.notificationDelivery.list";
	const PATH_INDEX = "pages.admin.notification_delivery";

    public function __construct(NotificationDeliveryRepository $_repo)
	{
		$this->_repo = $_repo;
		parent::__construct();
	}

    public function index(Request $request)
    {
      try {

          $item = $this->_repo->model->search( $request->only('eSign_destination') )
                    ->orderBy( $this->_order_by_default[0], $this->_order_by_default[1] )
                    ->paginate( $this->_limit );

          return view(self::PATH_INDEX.'.list',compact('item'));
      } catch (\Throwable $e) {
        report( $e->getMessage() );
        return redirect()->back()->withErrors( trans("common.error") );
      }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    // public function create()
    // {

    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NotificationDeliveryRequest $request)
    {
        try {

          $this->_repo->store(
            $request->only(
              'start_at',
              'title',
              'content',
              'destination',
            )
          );

			return redirect()->route(self::URL_INDEX)->with(['message'=>trans("common.success")]);

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NotificationDelivery  $notificationDeliverie
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
      try {
        $item = $this->_repo->fetchWhere([ 'id' => $id ])->first();
        return view(self::PATH_INDEX.'.edit',compact('item'));
      } catch (\Throwable $e) {
        report( $e->getMessage() );
        return redirect()->back()->withErrors( trans("common.error") );
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NotificationDelivery  $notificationDeliverie
     * @return \Illuminate\Http\Response
     */
    public function update(NotificationDeliveryRequest $request, $id)
    {
        try {

			      $_item = $this->_repo->fetchWhere([ 'id' => $id ])->first();
            $_item->update(
                          $request->only(
                            'start_at',
                            'title',
                            'content'
                          )
                    );

        DB::table('notifications')->where('notification_deliver_id',$_item->id)->update(   $request->only(
              'title',
              'content'
        ));

			return redirect()->route(self::URL_INDEX)->with(['message'=>trans("common.success")]);

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NotificationDelivery  $notificationDeliverie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
          DB::beginTransaction();

            $item = $this->_repo->fetchWhere( [ 'id' => $request->id ] )->first();
            DB::table('notifications')->where('notification_deliver_id',$item->id)->delete();
            $item->delete();

          DB::commit();
          return redirect()->route(self::URL_INDEX)->with(['message'=>trans("common.success")]);
        } catch (Throwable $e) {
          DB::rollBack();
          report( $e->getMessage() );
          return redirect()->back()->withErrors( trans("common.error") );
        }
    }
}
