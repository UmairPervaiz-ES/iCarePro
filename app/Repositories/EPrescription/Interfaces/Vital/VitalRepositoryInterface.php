<?php

namespace App\Repositories\EPrescription\Interfaces\Vital;

interface VitalRepositoryInterface 
{
  public function setBloodPressureVital($request);

  public function setHeightVital($request);

  public function setWeightVital($request);

  public function setHeartRateVital($request);

  public function setPulseVital($request);

  public function setRespiratoryRateVital($request);

  public function setTemperatureVital($request);

  public function setPainScaleVital($request);

  public function setInhaledO2Vital($request);

  public function getPatientVitals($request);

  public function setWcVital($request);

  public function setBmiVital($request);
  
}