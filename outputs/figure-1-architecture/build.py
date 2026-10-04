"""Rebuild the current icon-based architecture exports."""
from pathlib import Path
import runpy
runpy.run_path(str(Path(__file__).with_name("icon_build.py")), run_name="__main__")
