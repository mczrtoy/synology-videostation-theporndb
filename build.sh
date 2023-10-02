#!/bin/bash

source .build/setenv.sh


_PROJECT=build.xml
_TARGET=build
_VERBOSITY=detailed

usage() {
    cat <<EOF

Usage: $0 [TARGET] [OPTION]
    TARGET          analyze|build|clean|compile|package|rebuild|test
    OPTION
        --log       Creates an extensive build.log file
        --help      Shows this help
EOF
}

error() {
    echo
    echo -e "\033[0;31m${1}\033[0m ${2}"
}

failed() {
    echo
    echo -e "\033[41m                                                                                \033[0m"
    echo -e "\033[1;41mThe build failed                                                                \033[0m"
    echo -e "\033[41m                                                                                \033[0m"

    exit 1
}

# Parse command line argument values
# Note: Currently, last one on the command line wins (ex: rebuild clean == clean)
for i in "$@"; do
    case $1 in
        analyze|build|compile|package|rebuild|test) _TARGET=$1 ;;
        clean) _TARGET=clean; rm -rf .tmp/; rm -rf tmp ;;
        -l|--log) _VERBOSITY=diagnostic ;;
        --help) usage; exit 0 ;;
        *) error "Unknown option" "$1"; usage; failed ;;
    esac
    shift
done



./.build/composer.sh install -q -n
if [ $? -ne 0 ]; then
    failed
fi

./vendor/bin/phing -f $_PROJECT $_TARGET
if [ $? -ne 0 ]; then
    failed
fi