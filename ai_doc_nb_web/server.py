#!/usr/bin/env python3
"""
Simple Python Web Server for ai_doc_nb_web documentation.
Usage:
    python server.py [port] [--open]
"""

import argparse
import functools
import http.server
import os
import socket
import webbrowser
from pathlib import Path

DEFAULT_PORT = 8888
DOC_DIR = Path(__file__).resolve().parent


class DocHTTPRequestHandler(http.server.SimpleHTTPRequestHandler):
    """Custom HTTP request handler serving ai_doc_nb_web with UTF-8 and no-cache headers."""

    def __init__(self, *args, **kwargs):
        super().__init__(*args, directory=str(DOC_DIR), **kwargs)

    def end_headers(self):
        # Disable caching so documentation updates appear immediately
        self.send_header("Cache-Control", "no-store, no-cache, must-revalidate, max-age=0")
        self.send_header("Pragma", "no-cache")
        self.send_header("Expires", "0")
        super().end_headers()

    def guess_type(self, path):
        base_type = super().guess_type(path)
        if base_type in ("text/html", "text/plain", "text/css", "application/javascript"):
            return f"{base_type}; charset=utf-8"
        return base_type


def get_local_ip() -> str:
    try:
        with socket.socket(socket.AF_INET, socket.SOCK_DGRAM) as s:
            s.connect(("8.8.8.8", 80))
            return s.getsockname()[0]
    except Exception:
        return "127.0.0.1"


def main():
    parser = argparse.ArgumentParser(description="Serve ai_doc_nb_web documentation.")
    parser.add_argument(
        "port",
        nargs="?",
        type=int,
        default=DEFAULT_PORT,
        help=f"Port to listen on (default: {DEFAULT_PORT})",
    )
    parser.add_argument(
        "--host",
        default="0.0.0.0",
        help="Host interface to bind (default: 0.0.0.0)",
    )
    parser.add_argument(
        "--open",
        action="store_true",
        help="Automatically open the documentation in the default web browser",
    )
    args = parser.parse_args()

    os.chdir(DOC_DIR)
    server_address = (args.host, args.port)
    httpd = http.server.ThreadingHTTPServer(server_address, DocHTTPRequestHandler)

    local_url = f"http://localhost:{args.port}/"
    lan_url = f"http://{get_local_ip()}:{args.port}/"

    print("=" * 60)
    print(" Mini NB Doc Server (ai_doc_nb_web)")
    print("=" * 60)
    print(f" Serving directory : {DOC_DIR}")
    print(f" Local URL         : {local_url}")
    print(f" Network URL       : {lan_url}")
    print(" Press Ctrl+C to stop the server.")
    print("=" * 60)

    if args.open:
        webbrowser.open(local_url)

    try:
        httpd.serve_forever()
    except KeyboardInterrupt:
        print("\nShutting down server...")
    finally:
        httpd.server_close()


if __name__ == "__main__":
    main()
