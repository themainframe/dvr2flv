#!/bin/bash


/opt/local/bin/ffmpeg -f h264 -i "$1.h264" "$1" 2>&1