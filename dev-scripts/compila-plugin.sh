#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
CDIR=$( pwd )
cd $DIR/../
docker run -it -v `pwd`:/compilar node:16 bash -c "cd compilar && npm install && npm run build"
