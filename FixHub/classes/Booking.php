<?php

class Booking
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($userId, $serviceId, $date, $time, $notes)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO bookings
            (user_id, service_id, booking_date, booking_time, notes)
            VALUES
            (:user_id, :service_id, :booking_date, :booking_time, :notes)"
        );

        return $stmt->execute([
            ":user_id" => $userId,
            ":service_id" => $serviceId,
            ":booking_date" => $date,
            ":booking_time" => $time,
            ":notes" => $notes
        ]);
    }
}