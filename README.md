# ECTS Calculator

Code for a PHP ECTS calculator. An online version is available [here](https://ects.lrk.tools).

This calculator helps you to calculate a course's ECTS given the total actual hours of work a student will perform. The [ECTS User Guide of the European Union](https://op.europa.eu/s/n9bG) states:

> The correspondence of the full-time workload of an academic year to 60 credits is often formalised by national legal provisions. In most cases, workload ranges from 1,500 to 1,800 hours for an academic year, which means that one credit corresponds to 25 to 30 hours of work.

As such, the number of credits for a course depends on the total amount of hours worked for that course and on the total yearly workload. If you know the exact full-time workload of an academic year for your institution, this can optionally be indicated; otherwise, the guideline of 1500 to 1800 hours will be used and an ECTS range will be given.

The used formula is:

`((class hours * class hour duration) + (other hours * other hour duration)) / 60 minutes * number of weeks / (total workload / 60 ects)`