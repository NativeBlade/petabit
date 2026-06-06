<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * The server rejected our bearer token (HTTP 401): the session is stale or
 * revoked. The app must drop the local auth state and send the user back to
 * the login screen — staying "logged in" with a dead token is the bug this
 * replaces.
 */
class UnauthenticatedException extends RuntimeException {}
