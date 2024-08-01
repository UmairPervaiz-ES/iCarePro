<?php

namespace App\Repositories\Practice\Interfaces\Initial;

interface InitialRepositoryInterface
{
    public function initialPractice($request);
    public function practiceRequest($request, $id);
    public function practiceDocument($request,$id);
    public function practiceDocumentDelete($id);
    public function allNotifications($request);
    public function markNotificationAsRead($request);
    public function markAllNotificationsAsRead($request);
    public function contactPersonEmailCheck($request);

}
