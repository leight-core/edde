<?php
declare(strict_types=1);

namespace Edde\Job\Schema;

interface JobStatus {
    /**
     * Job has been created and currently no body cares about it (until a scheduler pick it up).
     *
     * In a very simple scenario this state could be omitted (thus all jobs could be scheduled for execution).
     *
     * In this state, job scheduler cares about the job.
     */
    const JOB_PENDING = 0;
    /**
     * Job has been scheduled for execution; in this state, job executor cares about this job.
     */
    const JOB_SCHEDULED = 1;
    /**
     * When a job is physically (you know, physically-virtually) executed, it's a running state.
     *
     * This is in job executor's space.
     */
    const JOB_RUNNING = 2;
    /**
     * Job is done. And it means **done**. If there is some error or whatever, it's saved in a response DTO.
     *
     * After this, nobody cares about the job again.
     *
     * Ok, nobody at all.
     */
    const JOB_SUCCESS = 3;
    /**
     * Job execution died. This status is generally used to detect something wrong happened.
     */
    const JOB_ERROR = 4;
    /**
     * When a job is cancelled, this state should be used.
     */
    const JOB_INTERRUPTED = 5;
    /**
     * When a job gets some error/warnings during import.
     */
    const JOB_CHECK = 6;
}
