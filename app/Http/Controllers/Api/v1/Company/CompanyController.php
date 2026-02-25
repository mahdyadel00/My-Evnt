<?php

namespace App\Http\Controllers\Api\v1\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Company\CheckQrCode;
use App\Http\Resources\Api\v1\ErrorResource;
use App\Http\Resources\Api\v1\EventResource;
use App\Http\Resources\Api\v1\OrderResource;
use App\Http\Resources\Api\v1\SuccessResource;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketQr;
use App\Models\FromServayHR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{

    public function checkQrCode(CheckQrCode $request)
    {
        try {
            DB::beginTransaction();

            $order = Order::where('order_number', $request->qr_code)->lockForUpdate()->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'ticket_status' => 'not_found',
                    'message' => 'Ticket not found',
                    'data' => null,
                ], 200);
            }

            $event = Event::where(['id' => $order->event_id])->first();

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'ticket_status' => 'event_not_found',
                    'message' => 'Event not found',
                    'data' => null,
                ], 200);
            }

            if ($event->company_id != auth()->guard('organization')->user()->id) {
                return response()->json([
                    'success' => false,
                    'ticket_status' => 'not_allowed',
                    'message' => 'You are not allowed to check this ticket',
                    'data' => null,
                ], 200);
            }
            $survey = FromServayHR::where(['event_id' => $event->id, 'email' => $order->user->email])->first();

            if (!$survey) {
                return response()->json([
                    'success' => false,
                    'ticket_status' => 'form_servay_hr_not_found',
                    'message' => 'Form Servay HR not found',
                    'data' => null,
                ], 200);
            }
        

            $order->update([
                'status' => 'checked',
                'checked_at' => now(),
            ]);

            if($order->status == 'checked') {
                $survey->update([
                    'status' => 'checked',
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'ticket_status' => 'checked',
                'message' => 'QR Code checked successfully',
                'data' => new OrderResource($order),
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
dd($e->getMessage());
            Log::error('checkQrCode error', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'ticket_status' => 'error',
                'message' => 'System error',
                'data' => null,
            ], 200);
        }
    }



    // public function checkQrCode(CheckQrCode $request)

    // {

    //     try {
    //         DB::beginTransaction();
    //         $ticket = TicketQr::where(['qr_code' => $request->qr_code])->first();

    //         $order = Order::where(['ticket_id' => $ticket->ticket_id])->first();

    //         //get event
    //         $event = Event::where(['id' => $order->event_id])->first();

    //         if (!$event) {
    //             return new ErrorResource([
    //                 'message' => 'Event not found',
    //                 'status' => 404
    //             ]);
    //         }

    //         //check if the found company
    //         if ($event->company_id != auth()->guard('organization')->user()->id) {
    //             return new ErrorResource([
    //                 'message' => 'You are not allowed to check this ticket',
    //                 'status' => 403
    //             ]);
    //         }

    //         if ($order->status == 'pending' || $order->status == 'exited') {
    //             try {
    //                 $order->status = 'checked';
    //                 $order->save();

    //                 DB::commit();
    //                 return response()->json([
    //                     'message' => 'QR Code checked successfully',
    //                     'status' => 200,
    //                     'ticket_status' => $order->status,
    //                     'data' => new OrderResource($order)
    //                 ]);
    //             } catch (\Exception $e) {
    //                 DB::rollBack();
    //                 dd($e->getMessage());
    //                 Log::channel('error')->error('Error in CompanyController@checkQrCode: ' . $e->getMessage() . ' at Line: ' . $e->getLine() . ' in File: ' . $e->getFile());
    //                 return new ErrorResource(['message' => 'Something went wrong', 'status' => 500]);
    //             }
    //         } else {
    //             return new ErrorResource([
    //                 'message' => 'QR Code is already checked',
    //                 'status' => 400
    //             ]);
    //         }

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         dd($e->getMessage());
    //         Log::channel('error')->error('Error in CompanyController@checkQrCode: ' . $e->getMessage() . ' at Line: ' . $e->getLine() . ' in File: ' . $e->getFile());
    //         return new ErrorResource([
    //             'message' => 'Something went wrong',
    //             'status' => 500
    //         ]);
    //     }
    // }

    public function checkQrCodeExited(CheckQrCode $request)
    {
        try {
            DB::beginTransaction();
            $ticket = TicketQr::where(['qr_code' => $request->qr_code])->first();

            $order = Order::where(['ticket_id' => $ticket->ticket_id])->first();

            //get event
            $event = Event::where(['id' => $order->event_id])->first();

            if (!$event) {
                return new ErrorResource([
                    'message' => 'Event not found',
                    'status' => 404
                ]);
            }

            //check if the found company
            if ($event->company_id != auth()->guard('organization')->user()->id) {
                return new ErrorResource([
                    'message' => 'You are not allowed to check this ticket',
                    'status' => 403
                ]);
            }

            if ($order->status == 'pending') {
                try {
                    $order->status = 'exited';
                    $order->save();

                    DB::commit();
                    return (new SuccessResource([
                        'message' => 'QR Code checked successfully',
                        'order_status' => $order->status,
                        'data' => new OrderResource($order)
                    ]))->withWrappData();
                } catch (\Exception $e) {
                    DB::rollBack();
                    dd($e->getMessage());
                    Log::channel('error')->error('Error in CompanyController@checkQrCodeExited: ' . $e->getMessage() . ' at Line: ' . $e->getLine() . ' in File: ' . $e->getFile());
                    return new ErrorResource(['message' => 'Something went wrong', 'status' => 500]);
                }
            } else {
                return new ErrorResource([
                    'message' => 'QR Code is not Existed yet',
                    'status' => 400
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            Log::channel('error')->error('Error in CompanyController@checkQrCodeExited: ' . $e->getMessage() . ' at Line: ' . $e->getLine() . ' in File: ' . $e->getFile());
            return new ErrorResource([
                'message' => 'Something went wrong',
                'status' => 500
            ]);
        }
    }

    public function changeOrderStatus(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $order = Order::find($id);

            if (!$order) {
                return ErrorResource::make(__('Order Not Found'));
            }
            if ($order->status == 'checked') {
                return ErrorResource::make(__('Order Already Checked'));
            } else {
                $order->status = 'checked';
                $order->save();
            }

            DB::commit();
            return SuccessResource::make([
                'message' => __('Order Status Updated'),
                'status' => 'checked',
            ])->withWrappData();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in AuthController@changeOrderStatus: ' . $e->getMessage() . ' at Line: ' . $e->getLine() . ' in File: ' . $e->getFile());
            return ErrorResource::make(__('Order Status Update Failed'));
        }
    }
}
