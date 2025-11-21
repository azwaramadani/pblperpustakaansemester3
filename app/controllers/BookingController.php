<?php
// proses_booking.php
require_once __DIR__ . '/../../core/Session.php';

Class bookingController{






























    
    public function cancel($bookingId)
        {
            Session::checkUserLogin();
            $userId = Session::get('user_id');
            $bookingModel = new Booking();
            
            $booking = $bookingModel->getHistoryByUser($bookingId, $userId);
            if (!$booking) {
                http_response_code(404);
                exit('Booking tidak ditemukan.');
            }
            if ($booking['status_booking'] !== 'Menunggu') {
                Session::set('flash_error', 'Booking tidak bisa dibatalkan (bukan status Menunggu).');
                header('Location: ?route=User/riwayat');
                exit;
            }

            $bookingModel->cancelByUser($bookingId, $userId);
            Session::set('flash_success', 'Booking berhasil dibatalkan.');
            header('Location: ?route=User/riwayat');
            exit;
        }
    }
