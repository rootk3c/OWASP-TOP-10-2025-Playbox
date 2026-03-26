from setuptools import setup, find_packages

setup(
    name='reqeusts',
    version='2.31.0',
    packages=find_packages(),
    install_requires=['requests==2.31.0'], # Pulls the real library so the app works!
    description='A completely legitimate HTTP library. Definitely no backdoors here.',
)

