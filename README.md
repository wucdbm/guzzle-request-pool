# Async GuzzleHttp Request Pool

Tired of your GuzzleHttp request pool randomly not working?
Tired of trying to get concurrency just right so Guzzle will actually execute *ALL* of your requests?
Tired of pulling your hair because your last async request is not executing and you don't know why?

You've come to the right place!

Just use `Wucdbm\GuzzleHttp\Pool` like you would `GuzzleHttp\Pool` and enjoy cold beer ;)

In fact, Guzzle's Pool was working fine for the most part, except several lines of code where the design was plain wrong.
The problem manifests itself when you send an arbitrary number of additional requests from your fulfilled handlers.
This library solves that problem.

Credits to the GuzzleHttp team for creating this awesome library and the pool, I just fixed it.
As my changes swayed away from just one or two lines of code during development, I decided to publish this small library instead.
It should be fully compatible with Guzzle `"guzzlehttp/guzzle": "~6.0"`. If you encounter any issues, please let me know.

Original comments from Guzzle's code I used as a base are (mostly) preserved.