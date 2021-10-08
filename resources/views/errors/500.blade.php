@extends('errors::illustrated-layout')

@section('title', __('Import Error'))
@section('code', '500')
@section('message', __('Data import salah! Pastikan file berekstensi .csv dan sesuai format yang ditentukan'))
