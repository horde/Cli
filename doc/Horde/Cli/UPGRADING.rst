=====================
 Upgrading Horde_Cli
=====================

:Contact: dev@lists.horde.org

.. contents:: Contents
.. section-numbering::


This lists the API changes between releases of the package.


Upgrading to 2.3.0
==================

  - Horde_Cli

    - Constructor

      An $opts parameter has been added to pass optional 'output' and 'pager'
      options.


Upgrading to 2.2.0
==================

  - Horde_Cli_Color

    This class has been added.

  - Horde_Cli

    - block(), getWidth()

      These methods have been added.

    - bold(), red(), green(), blue(), yellow()

      These methods have been deprecated. Use Horde_Cli_Color or
      Horde_Cli#color() instead.


Upgrading to 2.1.0
==================

  - Horde_Cli

    - color(), header()

      These methods have been added.
