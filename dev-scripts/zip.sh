#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
CDIR=$( pwd )
cd $DIR/../trunk/
zip -r zip/concordamos.zip ./ -x "./node_modules/*" -x "./dev-scripts/*" -x "./zip/*" -x "./.*"
