#!/usr/bin/env python3
"""Report the transitive include_once graph for a list of PHP files.

For usage, run with --help

Known to work with Python 3.8 or later on Linux and macOS, but should
also work with any Python 3.x on any supported Python platform.
"""

import argparse
import os
import re
import sys

from collections import defaultdict
from contextlib import suppress

def get_deps(filename: str, get_all: bool, dep_graph: dict):
    """Get the transitive include dependencies for filename, updating dep_graph"""
    with suppress(FileNotFoundError):
        with open(filename, 'r') as f:
            for l in f:
                # Assuming code is well indented, conditional includes
                # won't be at the start of a line.
                anchor = "" if get_all else "^"
                m = re.search(anchor + r"(require|include)_once *\(([^)]+)\);", l)
                if not m:
                    continue
                # Do some 'good enough' parsing of $relPath.'foo.inc'
                p = m[2].replace('"', '\'')
                m2 = re.match(r"\$relPath\.'([^']+)'", p)
                if m2:
                    p = 'pinc/' + m2[1]
                if '$relPath' not in p:
                    p = os.path.normpath(p)
                    dep_graph[filename].append(p)
            for dep in dep_graph[filename]:
                if dep not in dep_graph:
                    get_deps(dep, get_all, dep_graph)

def main() -> int:
    p = argparse.ArgumentParser()
    p.add_argument('--mode',
                   type=str,
                   default='graph',
                   help='Produce a graphviz graph of dependencies, or output a list of dependencies, one per line',
                   choices=['graph', 'deps']
    )
    p.add_argument('--get-all',
                   action='store_true',
                   help='get all dependencies, including conditional ones.'
    )
    p.add_argument('files',
                   nargs='+',
                   help='PHP files to scan for dependencies'
    )
    args = p.parse_args()

    dep_graph = defaultdict(list)
    for fn in args.files:
        get_deps(fn, args.get_all, dep_graph)

    if args.mode == 'graph':
        print("digraph deps {")
        for fn, deps in dep_graph.items():
            print("".join(f'    "{fn}" -> "{d}";\n' for d in deps), end='')
        print("}")
    elif args.mode == 'deps':
        deps = set()
        for d in dep_graph.values():
            deps.update(d)
        print("\n".join(sorted(deps)))

    return 0

if __name__ == '__main__':
    sys.exit(main())
