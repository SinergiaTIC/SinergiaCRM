<?php

interface stic_MessagesHelper {
    public function sendMessage(?string $from, string $text, string $to): array;
}