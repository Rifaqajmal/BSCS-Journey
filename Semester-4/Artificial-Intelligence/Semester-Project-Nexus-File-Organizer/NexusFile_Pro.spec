# -*- mode: python ; coding: utf-8 -*-

from PyInstaller.utils.hooks import collect_all

# Collect full pandas and matplotlib contents
datas_pandas, binaries_pandas, hiddenimports_pandas = collect_all('pandas')
datas_matplotlib, binaries_matplotlib, hiddenimports_matplotlib = collect_all('matplotlib')

block_cipher = None

a = Analysis(
    ['NexusFile_Pro.py'],
    pathex=['.'],  # current directory
    binaries=binaries_pandas + binaries_matplotlib,
    datas=datas_pandas + datas_matplotlib,
    hiddenimports=[
        'PIL.Image',
        'customtkinter'
    ] + hiddenimports_pandas + hiddenimports_matplotlib,
    hookspath=[],
    hooksconfig={},
    runtime_hooks=[],
    excludes=[],
    win_no_prefer_redirects=False,
    win_private_assemblies=False,
    cipher=block_cipher,
    noarchive=False
)

pyz = PYZ(
    a.pure,
    a.zipped_data,
    cipher=block_cipher
)

exe = EXE(
    pyz,
    a.scripts,
    [],
    exclude_binaries=True,
    name='NexusFile_Pro',
    debug=False,
    bootloader_ignore_signals=False,
    strip=False,
    upx=True,
    console=False  # set to True for debugging
)

coll = COLLECT(
    exe,
    a.binaries,
    a.datas,
    strip=False,
    upx=True,
    upx_exclude=[],
    name='NexusFile_Pro'
)
